<?php

declare(strict_types=1);

namespace App\Mutant;

/**
 * Trait MessagesTrait
 * @package App\Mutant
 */
trait MessagesTrait
{
    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }
}

