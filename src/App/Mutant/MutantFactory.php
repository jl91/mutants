<?php

declare(strict_types=1);

namespace App\Mutant;

use Psr\Container\ContainerInterface;

class MutantFactory
{
    public function __invoke(ContainerInterface $container): Mutant
    {
        return new Mutant($container->get(MutantService::class));
    }
}
