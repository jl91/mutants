<?php

declare(strict_types=1);

namespace App\Mutant\Handler;

use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
use Psr\Container\ContainerInterface;

class MutantHandlerFactory
{
    public function __invoke(ContainerInterface $container): MutantHandler
    {
        return new MutantHandler(
            $container->get(MutantService::class),
            new MutantDNAValidator()
        );
    }
}
