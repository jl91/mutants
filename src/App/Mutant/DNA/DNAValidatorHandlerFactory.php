<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

use Psr\Container\ContainerInterface;

/**
 * Class DNAValidatorHandlerFactory
 * @package App\Mutant\DNA
 */
class DNAValidatorHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return DNAValidatorHandler
     */
    public function __invoke(ContainerInterface $container): DNAValidatorHandler
    {
        return new DNAValidatorHandler(new DNAValidator());
    }
}
