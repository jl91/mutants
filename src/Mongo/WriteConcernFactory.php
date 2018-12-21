<?php
declare(strict_types=1);

namespace Mongo;

use MongoDB\Driver\WriteConcern;
use Psr\Container\ContainerInterface;

/**
 * Class WriteConcernFactory
 * @package App\Mongo
 * @codeCoverageIgnore
 * ignorada no codecoverage pois não faz sentido testar um pacote externo.
 */
class WriteConcernFactory
{

    /**
     * @param ContainerInterface $container
     * @return MutantService
     */
    public function __invoke(ContainerInterface $container): WriteConcern
    {
        return new WriteConcern(WriteConcern::MAJORITY, 1000);
    }
}



