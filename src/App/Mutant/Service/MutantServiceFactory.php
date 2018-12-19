<?php

declare(strict_types=1);

namespace App\Mutant\Service;

use Psr\Container\ContainerInterface;

class MutantServiceFactory
{
    public function __invoke(ContainerInterface $container): MutantService
    {
        return new MutantService();
    }
}
