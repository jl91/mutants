<?php

declare(strict_types=1);

namespace App\Mutant\MutantDNA;

use App\Mutant\MessagesTrait;
use Zend\Validator\ValidatorInterface;

/**
 * Class MutantDNAValidator
 * @package App\Mutant\MutantDNA
 */
class MutantDNAValidator implements ValidatorInterface
{
    /**
     * Tamanho mínimo trabalhado internamente
     */
    private const MINIMUM_SIZE = 0;
    /**
     * Tamanho mínimo trabalhado internamente
     */
    private const MINIMUM_MUTANT_SIZE = 4;
    /**
     * ADENINE_REQUENCE
     */
    const ADENINE_REQUENCE = 'AAAA';
    /**
     * CYTOSINE_REQUENCE
     */
    const CYTOSINE_REQUENCE = 'CCCC';
    /**
     * GUANINE_REQUENCE
     */
    const GUANINE_REQUENCE = 'GGGG';
    /**
     *THYMINE_REQUENCE
     */
    const THYMINE_REQUENCE = 'TTTT';
    /**
     * @var string
     */
    private $messages = '';

    /**
     * @param $value
     * @return bool
     */
    public function isMutant($value): bool
    {
        return $this->isValid($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $dnaMatrix = $this->convertToMatrix($value);

        $rows = count($dnaMatrix);
        $columns = count($dnaMatrix[self::MINIMUM_SIZE]);

        if ($rows < self::MINIMUM_MUTANT_SIZE ||
            $columns < self::MINIMUM_MUTANT_SIZE
        ) {
            $this->messages = 'Input nitrogenous bases has no minimum size to be from a mutant';
            return false;
        }

        $hasNitrogenousBasesSequenceInRow = self::MINIMUM_SIZE;
        $hasNitrogenousBasesSequenceInColumn = self::MINIMUM_SIZE;
        $diagonals = [];

        foreach ($dnaMatrix as $row => $columns) {
            $hasNitrogenousBasesSequenceInRow += $this->hasNitrogenousBasesSequenceInRow($dnaMatrix, $row);
            foreach ($columns as $column => $value) {
                $hasNitrogenousBasesSequenceInColumn += $this->hasNitrogenousBasesSequenceInColumn($dnaMatrix, $column);

                if ($row === $column) {
                    $diagonals[] = $dnaMatrix[$row][$column];
                }
            }
        }

        $hasNitrogenousBasesSequenceInDiagonal = (int)$this->hasNitrogenousBasesInSequence(implode('', $diagonals));

        return $hasNitrogenousBasesSequenceInRow > self::MINIMUM_SIZE &&
            $hasNitrogenousBasesSequenceInColumn > self::MINIMUM_SIZE ||
            $hasNitrogenousBasesSequenceInColumn > self::MINIMUM_SIZE &&
            $hasNitrogenousBasesSequenceInDiagonal > self::MINIMUM_SIZE ||
            $hasNitrogenousBasesSequenceInRow > self::MINIMUM_SIZE &&
            $hasNitrogenousBasesSequenceInDiagonal > self::MINIMUM_SIZE;
    }

    /**
     * @param array $value
     * @return array
     */
    private function convertToMatrix(array $value): array
    {
        array_walk($value, function (&$item) {
            $item = \str_split($item);
        });

        return $value;
    }

    /**
     * @param array $dnaMatrix
     * @param int $row
     * @return int
     */
    private function hasNitrogenousBasesSequenceInRow(array $dnaMatrix, int $row): int
    {
        $sequence = implode('', $dnaMatrix[$row]);
        return (int)$this->hasNitrogenousBasesInSequence($sequence);
    }

    /**
     * @param string $sequence
     * @return bool
     */
    private function hasNitrogenousBasesInSequence(string $sequence): bool
    {
        return \strpos($sequence, self::ADENINE_REQUENCE) !== false ||
            \strpos($sequence, self::CYTOSINE_REQUENCE) !== false ||
            \strpos($sequence, self::GUANINE_REQUENCE) !== false ||
            \strpos($sequence, self::THYMINE_REQUENCE) !== false;
    }

    /**
     * @param $dnaMatrix
     * @param $currentColumn
     * @return int
     */
    private function hasNitrogenousBasesSequenceInColumn($dnaMatrix, $currentColumn): int
    {
        $columnPieces = array_column($dnaMatrix, $currentColumn);
        $currentColumn = implode('', $columnPieces);
        return (int)$this->hasNitrogenousBasesInSequence($currentColumn);
    }

    use MessagesTrait;
}
