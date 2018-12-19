<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

use Psr\Container\ContainerInterface;

class DNAValidatorHandlerFactory
{
    public function __invoke(ContainerInterface $container): DNAValidatorHandler
    {
        return new DNAValidatorHandler(new DNAValidator());
    }
}
