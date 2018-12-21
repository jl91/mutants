<?php

declare(strict_types=1);

namespace App\Mutant\Service;

use MongoDB\Driver\Manager;
use MongoDB\Driver\WriteConcern;
use Psr\Container\ContainerInterface;

/**
 * Class MutantServiceFactory
 * @package App\Mutant\Service
 * @codeCoverageIgnore
 * Ignorado pq a classe do provida do driver do mongo é final,  é possível aplicar reflection em final classes
 * con isso não é possivel criar um mock dele.
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
