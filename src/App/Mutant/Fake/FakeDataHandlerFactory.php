<?php

declare(strict_types=1);

namespace App\Mutant\Fake;

use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
use Psr\Container\ContainerInterface;

/**
 * Class FakeDataHandlerFactory
 * @package App\Mutant\Fake
 * @codeCoverageIgnore
 */
class FakeDataHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return FakeDataHandler
     */
    public function __invoke(ContainerInterface $container): FakeDataHandler
    {
        return new FakeDataHandler(
            $container->get(MutantService::class),
            new MutantDNAValidator()
        );
    }
}
