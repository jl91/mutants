<?php

declare(strict_types=1);

namespace App\Mutant\Service;

use App\Mutant\DNA\DNAEntity;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\Exception as MongoDBDriverException;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

class MutantService
{
    const MAGNETO_NAMESPACE = 'magneto.mutants';

    private $mongo = null;
    private $writeConcern = null;

    public function __construct(
        Manager $mongo,
        WriteConcern $writeConcern
    )
    {
        $this->mongo = $mongo;
        $this->writeConcern = $writeConcern;
    }

    public function persist(array $data): bool
    {
        $hasData = count($this->find($data)->toArray());

        if ($hasData) {
            return false;
        }

        try {
            $bulk = new BulkWrite(['ordered' => false]);

            $prepareData = $this->prepareData($data);

            $bulk->insert($prepareData);

            $result = $this->mongo
                ->executeBulkWrite(self::MAGNETO_NAMESPACE, $bulk, $this->writeConcern);
            $insertedCount = $result->getInsertedCount();
            return $insertedCount !== null && $insertedCount > 0;

        } catch (BulkWriteException $e) {
            $result = $e->getWriteResult();

            // Check if the write concern could not be fulfilled
            if ($writeConcernError = $result->getWriteConcernError()) {
                printf("%s (%d): %s\n",
                    $writeConcernError->getMessage(),
                    $writeConcernError->getCode(),
                    var_export($writeConcernError->getInfo(), true)
                );
            }

            // Check if any write operations did not complete at all
            foreach ($result->getWriteErrors() as $writeError) {
                printf("Operation#%d: %s (%d)\n",
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

    public function find($data): Cursor
    {
        $query = new Query(['data' => implode('|', $data)]);
        return $this->mongo
            ->executeQuery(self::MAGNETO_NAMESPACE, $query);
    }

    private function prepareData(array $data): DNAEntity
    {
        $dnaEntity = new DNAEntity();
        $dnaEntity->data = implode('|', $data);
        $dnaEntity->rows = count($data);
        $dnaEntity->columns = strlen($data[0]);
        return $dnaEntity;
    }
}