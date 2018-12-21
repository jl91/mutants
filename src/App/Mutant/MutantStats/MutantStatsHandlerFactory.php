<?php

declare(strict_types=1);

namespace App\Mutant\MutantStats;

use App\Mutant\Service\MutantService;
use Psr\Container\ContainerInterface;

class MutantStatsHandlerFactory
{
    public function __invoke(ContainerInterface $container): MutantStatsHandler
    {
        return new MutantStatsHandler($container->get(MutantService::class));
    }
}
