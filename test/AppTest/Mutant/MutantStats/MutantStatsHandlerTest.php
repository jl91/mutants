<?php

namespace AppTest\Mutant\MutantStats;

use App\Mutant\MutantStats\MutantStatsEntity;
use App\Mutant\MutantStats\MutantStatsHandler;
use App\Mutant\Service\MutantService;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

/**
 * Class MutantStatsHandlerTest
 * @package AppTest\Mutant\MutantStats
 * @group MutantStatsHandlerTest
 */
class MutantStatsHandlerTest extends TestCase
{
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;

    /**
     * @var MutantStatsHandler
     */
    private $instance;

    public function setUp()
    {
        $this->container = ContainerMock::getinstance();
        $this->instance = new MutantStatsHandler($this->container->get(MutantService::class));
    }

    public function test__construct()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->container);
    }

    public function testHandleSuccessScenario()
    {

        $actual = $this->instance
            ->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $actual);

        $actualStatus = $actual->getStatusCode();
        $this->assertEquals(Response::STATUS_CODE_200, $actualStatus);

        $actualResponseBody = $actual->getBody()->getContents();
        $expected = json_encode(new MutantStatsEntity());
        $this->assertEquals($expected, $actualResponseBody);

    }

    public function testHandleErrorScenario()
    {

        ContainerMock::$instances[MutantService::class]
            ->fetchStats()
            ->willThrow(new \Exception());

        $actual = $this->instance
            ->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $actual);

        $actualStatus = $actual->getStatusCode();
        $this->assertEquals(Response::STATUS_CODE_500, $actualStatus);

        $actualResponseBody = $actual->getBody()->getContents();
        $expected = json_encode([
            'error' => 'I\'m sorry, something went wrong!',
            'message' => ''
        ], JSON_HEX_APOS);
        $this->assertEquals($expected, $actualResponseBody);

    }
}
