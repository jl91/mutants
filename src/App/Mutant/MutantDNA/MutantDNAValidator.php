<?php

declare(strict_types=1);

namespace App\Mutant\MutantDNA;

use App\Mutant\MessagesTrait;
use Zend\Validator\ValidatorInterface;

class MutantDNAValidator implements ValidatorInterface
{
    private const MINIMUM_SIZE = 0;
    private const MINIMUM_MUTANT_SIZE = 4;
    const ADENINE_REQUENCE = 'AAAA';
    const CYTOSINE_REQUENCE = 'CCCC';
    const GUANINE_REQUENCE = 'GGGG';
    const THYMINE_REQUENCE = 'TTTT';
    private $messages = '';

    public function isMutant($value): bool
    {
        return $this->isValid($value);
    }

    public function isValid($value)
    {
        $dnaMatrix = $this->convertToMatrix($value);

        $rows = count($dnaMatrix);
        $columns = count($dnaMatrix[0]);

        if (
            $rows < self::MINIMUM_MUTANT_SIZE ||
            $columns < self::MINIMUM_MUTANT_SIZE
        ) {
            $this->messages = 'Input nitrogenous bases has no minimum size to be from a mutant';
            return false;
        }

        $hasNitrogenousBasesSequenceInRow = 0;
        $hasNitrogenousBasesSequenceInColumn = 0;
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

    private function convertToMatrix(array $value): array
    {
        array_walk($value, function (&$item) {
            $item = \str_split($item);
        });

        return $value;
    }

    private function hasNitrogenousBasesSequenceInRow(array $dnaMatrix, int $row): int
    {
        $sequence = implode('', $dnaMatrix[$row]);
        return (int)$this->hasNitrogenousBasesInSequence($sequence);
    }

    private function hasNitrogenousBasesInSequence(string $sequence): bool
    {
        return \strpos($sequence, self::ADENINE_REQUENCE) !== false ||
            \strpos($sequence, self::CYTOSINE_REQUENCE) !== false ||
            \strpos($sequence, self::GUANINE_REQUENCE) !== false ||
            \strpos($sequence, self::THYMINE_REQUENCE) !== false;
    }

    private function hasNitrogenousBasesSequenceInColumn($dnaMatrix, $currentColumn): int
    {
        $columnPieces = array_column($dnaMatrix, $currentColumn);
        $currentColumn = implode('', $columnPieces);
        return (int)$this->hasNitrogenousBasesInSequence($currentColumn);
    }

    use MessagesTrait;
}