<?php

declare(strict_types=1);

namespace App\Mutant\MutantStats;

use App\Mutant\Service\MutantService;
use Psr\Container\ContainerInterface;

/**
 * Class MutantStatsHandlerFactory
 * @package App\Mutant\MutantStats
 */
class MutantStatsHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return MutantStatsHandler
     */
    public function __invoke(ContainerInterface $container): MutantStatsHandler
    {
        return new MutantStatsHandler($container->get(MutantService::class));
    }
}
