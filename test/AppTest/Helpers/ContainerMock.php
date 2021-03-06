<?php
declare(strict_types=1);

namespace AppTest\Helpers;

use App\Mutant\DNA\DNAValidator;
use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\MutantStats\MutantStatsEntity;
use App\Mutant\Service\MutantService;
use http\Env\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContainerMock extends \PHPUnit\Framework\TestCase
{
    static $instances = [];

    private static $dependencies = [
        'config',
        RouterInterface::class,
        MutantService::class,
        ServerRequestInterface::class,
        \MongoDB\Driver\Manager::class,
        StreamInterface::class,
        MutantDNAValidator::class,
        DNAValidator::class,
        RequestHandlerInterface::class,
        ResponseInterface::class
    ];
    private static $configs = [
        'mongo'
    ];

    static function getinstance(): ContainerInterface
    {
        $selfInstance = new self();
        $container = $selfInstance->prophesize(ContainerInterface::class);


        foreach (self::$dependencies as $dependency) {
            self::$instances[$dependency] = $selfInstance->prophesize($dependency);

            $container->has($dependency)
                ->willReturn(true);

            $container->get($dependency)
                ->willReturn(self::$instances[$dependency]);
        }

        $container->get('config')
            ->willReturn(self::$configs);

        self::$instances[MutantService::class]->fetchStats()->willReturn(new MutantStatsEntity());

        return $container->reveal();
    }

}