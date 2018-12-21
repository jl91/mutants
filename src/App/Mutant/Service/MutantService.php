<?php

declare(strict_types=1);

namespace App\Mutant\Service;

use App\Mutant\DNA\DNAEntity;
use App\Mutant\MutantStats\MutantStatsEntity;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Command;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\Exception as MongoDBDriverException;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

/**
 * Class MutantService
 * @package App\Mutant\Service
 */
class MutantService
{
    /**
     *
     */
    const MAGNETO_NAMESPACE = 'magneto.mutants';

    /**
     * @var Manager|null
     */
    private $mongo = null;
    /**
     * @var WriteConcern|null
     */
    private $writeConcern = null;

    /**
     * MutantService constructor.
     * @param Manager $mongo
     * @param WriteConcern $writeConcern
     */
    public function __construct(
        Manager $mongo,
        WriteConcern $writeConcern
    ) {

        $this->mongo = $mongo;
        $this->writeConcern = $writeConcern;
    }

    /**
     * @param array $data
     * @param bool $isMutant
     * @return bool
     */
    public function persist(array $data, bool $isMutant): bool
    {
        $hasData = count($this->find($data)->toArray());

        if ($hasData) {
            return false;
        }

        try {
            $bulk = new BulkWrite(['ordered' => false]);

            $prepareData = $this->prepareData($data, $isMutant);

            $bulk->insert($prepareData);

            $result = $this->mongo
                ->executeBulkWrite(self::MAGNETO_NAMESPACE, $bulk, $this->writeConcern);
            $insertedCount = $result->getInsertedCount();
            return $insertedCount !== null && $insertedCount > 0;
        } catch (BulkWriteException $e) {
            $result = $e->getWriteResult();

            // Check if the write concern could not be fulfilled
            if ($writeConcernError = $result->getWriteConcernError()) {
                printf(
                    "%s (%d): %s\n",
                    $writeConcernError->getMessage(),
                    $writeConcernError->getCode(),
                    var_export($writeConcernError->getInfo(), true)
                );
            }

            // Check if any write operations did not complete at all
            foreach ($result->getWriteErrors() as $writeError) {
                printf(
                    "Operation#%d: %s (%d)\n",
                    $writeError->getIndex(),
                    $writeError->getMessage(),
                    $writeError->getCode()
                );
            }
        } catch (MongoDBDriverException $e) {
            printf("Other error: %s\n", $e->getMessage());
            exit;
        }
    }

    /**
     * @param $data
     * @return Cursor
     * @throws MongoDBDriverException
     */
    public function find($data): Cursor
    {
        $query = new Query(['data' => implode('|', $data)]);
        return $this->mongo
            ->executeQuery(self::MAGNETO_NAMESPACE, $query);
    }

    /**
     * @param array $data
     * @param bool $isMutant
     * @return DNAEntity
     */
    private function prepareData(array $data, bool $isMutant): DNAEntity
    {
        $dnaEntity = new DNAEntity();
        $dnaEntity->data = implode('|', $data);
        $dnaEntity->rows = count($data);
        $dnaEntity->columns = strlen($data[0]);
        $dnaEntity->isMutant = $isMutant;
        return $dnaEntity;
    }

    /**
     * @return MutantStatsEntity
     */
    public function fetchStats(): MutantStatsEntity
    {
        $mutantStatsEntity = new MutantStatsEntity();
        $mutantStatsEntity->count_mutant_dna = $this->fetchTotalDNA(true);
        $mutantStatsEntity->count_human_dna = $this->fetchTotalDNA(false);
        $mutantStatsEntity->ratio = $this->calculateRatio($mutantStatsEntity);
        return $mutantStatsEntity;
    }

    /**
     * @param $isMutant
     * @return int
     * @throws MongoDBDriverException
     */
    private function fetchTotalDNA($isMutant): int
    {
        $command = new Command([
            'aggregate' => 'mutants',
            'pipeline' => $this->getPipelineForStats($isMutant),
            'cursor' => new \stdClass()
        ]);

        $result = $this->mongo
            ->executeCommand('magneto', $command)
            ->toArray();

        if (count($result) &&
            isset($result[0]->{'COUNT(*)'})
        ) {
            return $result[0]->{'COUNT(*)'};
        }

        return 0;
    }

    /**
     * @param bool $isMutant
     * @return array
     */
    private function getPipelineForStats(bool $isMutant = false): array
    {
        return [
            [
                '$match' => [
                    'isMutant' => $isMutant
                ]
            ],
            [
                '$group' => [
                    '_id' => [],
                    'COUNT(*)' => [
                        '$sum' => 1
                    ]
                ]
            ],
            [
                '$project' => [
                    'COUNT(*)' => '$COUNT(*)',
                    '_id' => 0
                ]
            ]
        ];
    }

    /**
     * @param MutantStatsEntity $mutantStatsEntity
     * @return float
     */
    private function calculateRatio(MutantStatsEntity $mutantStatsEntity): float
    {
        $gdcObject = \gmp_gcd($mutantStatsEntity->count_mutant_dna, $mutantStatsEntity->count_human_dna);
        $gdc = \gmp_strval($gdcObject);
        return (float)($mutantStatsEntity->count_human_dna / $gdc . '.' . $mutantStatsEntity->count_mutant_dna / $gdc);
    }
}

