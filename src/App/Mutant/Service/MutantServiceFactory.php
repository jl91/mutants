<?php

declare(strict_types=1);

namespace App\Mutant\Service;

use MongoDB\Driver\Manager;
use MongoDB\Driver\WriteConcern;
use Psr\Container\ContainerInterface;

/**
 * Class MutantServiceFactory
 * @package App\Mutant\Service
 */
class MutantServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return MutantService
     */
    public function __invoke(ContainerInterface $container): MutantService
    {
        $mongo = $container->get(Manager::class);
        $writeConcern = $container->get(WriteConcern::class);
        return new MutantService($mongo, $writeConcern);
    }
}
