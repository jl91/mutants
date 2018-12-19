<?php

declare(strict_types=1);

namespace App\Mutant\MutantDNA;

use App\Mutant\MessagesTrait;
use Zend\Validator\ValidatorInterface;

class MutantDNAValidator implements ValidatorInterface
{

    public function isMutant($value): bool
    {
        return $this->isValid($value);
    }

    public function isValid($value)
    {
        $dnaMatrix = $this->convertToMatrix($value);
        var_dump($dnaMatrix);
        exit();
    }

    private function convertToMatrix(array $value): array
    {
        array_walk($value, function (&$item) {
            $item = \str_split($item);
        });

        return $value;
    }

    use MessagesTrait;
}