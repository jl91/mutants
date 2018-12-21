<?php
declare(strict_types=1);

namespace Mongo;

use MongoDB\Driver\Manager;
use Psr\Container\ContainerInterface;

/**
 * Class ManagerFactory
 * @package App\Mongo
 * @codeCoverageIgnore
 * ignorada no codecoverage pois não faz sentido testar um pacote externo.
 */
class ManagerFactory
{

    /**
     * @param ContainerInterface $container
     * @return MutantService
     */
    public function __invoke(ContainerInterface $container): Manager
    {
        $mongodb = $container->get('config')['mongodb'];
        $mongoUrl = \sprintf(
            "mongodb://%s:%s@%s:%s/%s",
            $mongodb['username'],
            $mongodb['password'],
            $mongodb['host'],
            $mongodb['port'],
            $mongodb['database']
        );
        return new Manager($mongoUrl);
    }
}

