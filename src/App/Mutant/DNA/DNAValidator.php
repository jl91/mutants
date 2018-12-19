<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

use Zend\Validator\ValidatorInterface;

class DNAValidator implements ValidatorInterface
{
    const VALID_LETTERS = [
        'A', 'T', 'C', 'G'
    ];

    private $messages = '';

    public function isValid($value): bool
    {

        if (!\is_array($value)) {
            $this->messages = "Input should be a array";
            return false;
        }

        foreach ($value as $row) {
            $pieces = \str_split($row);

            foreach ($pieces as $piece) {
                if (!\in_array($piece, self::VALID_LETTERS)) {
                    $this->messages = "Letters of DNA can only be A, T, C or G";
                    return false;
                }
            }
        }

        return true;
    }

    public function getMessages(): string
    {
        return $this->messages;
    }
}