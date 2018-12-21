<?php

declare(strict_types=1);

namespace App\Mutant\Service;

use \MongoDB\Driver\Manager;
use \MongoDB\Driver\WriteConcern;
use Psr\Container\ContainerInterface;

class MutantServiceFactory
{
    public function __invoke(ContainerInterface $container): MutantService
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
        $mongo = new Manager($mongoUrl);
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        return new MutantService($mongo, $writeConcern);
    }
}
