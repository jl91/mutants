<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

use App\Mutant\MessagesTrait;
use Zend\Validator\ValidatorInterface;

class DNAValidator implements ValidatorInterface
{
    const VALID_LETTERS = [
        'A', 'T', 'C', 'G'
    ];
    const MINIMUM_SIZE = 0;

    private $messages = '';

    public function isValid($value): bool
    {

        if (!\is_array($value)) {
            $this->messages = "Input nitrogenous bases should be a array";
            return false;
        }

        if (count($value) <= self::MINIMUM_SIZE) {
            $this->messages = "Input nitrogenous bases cannot be empty";
            return false;
        }

        foreach ($value as $row) {
            $pieces = \str_split($row);

            foreach ($pieces as $piece) {
                if (!\in_array($piece, self::VALID_LETTERS)) {
                    $this->messages = "Invalid nitrogenous bases found, it should be only A, T, C, G";
                    return false;
                }
            }
        }

        return true;
    }

    use MessagesTrait;
}