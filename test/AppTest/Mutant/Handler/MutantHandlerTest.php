<?php

namespace AppTest\Mutant\Handler;

use App\Mutant\Handler\MutantHandler;
use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

/**
 * Class MutantHandlerTest
 * @package AppTest\Mutant\Handler
 * @group MutantHandlerTest
 */
class MutantHandlerTest extends TestCase
{

    private const DATA = [
        "ATGCGA",
        "CAGTGC",
        "TTATGT",
        "AGAAGG",
        "CCCCTA",
        "TCACTG"
    ];
    /** @var Container|ObjectProphecy */
    protected $container;
    /**
     * @var
     */
    protected $instance;

    public function test__construct()
    {
        $actual = $this->instance;
        $this->assertInstanceOf(MutantHandler::class, $actual);
    }

    public function testHandle200()
    {
        $this->setRequestData();

        ContainerMock::$instances[MutantDNAValidator::class]
            ->isValid(self::DATA)
            ->willReturn(true);

        ContainerMock::$instances[MutantService::class]
            ->persist(self::DATA, true)
            ->willReturn(true);

        $response = $this->instance->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
        $this->assertEquals(json_encode(['isMutant' => true]), $response->getBody()->getContents());

    }

    public function testHandle403()
    {
        $this->setRequestData();

        ContainerMock::$instances[MutantDNAValidator::class]
            ->isValid(self::DATA)
            ->willReturn(false);

        ContainerMock::$instances[MutantService::class]
            ->persist(self::DATA, false)
            ->willReturn(true);

        $response = $this->instance->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_403, $response->getStatusCode());
        $this->assertEquals(json_encode(['isMutant' => false]), $response->getBody()->getContents());

    }

    private function setRequestData()
    {
        $requestContent = new \stdClass();
        $requestContent->dna = self::DATA;

        ContainerMock::$instances[StreamInterface::class]
            ->getContents()
            ->willReturn(json_encode($requestContent));

        ContainerMock::$instances[ServerRequestInterface::class]
            ->getBody()
            ->willReturn(ContainerMock::$instances[StreamInterface::class]);
    }

    public function testHandleException()
    {
        $this->setRequestData();

        ContainerMock::$instances[MutantService::class]
            ->persist(self::DATA, false)
            ->willThrow(new \Exception());

        ContainerMock::$instances[MutantDNAValidator::class]
            ->isValid(self::DATA)
            ->willReturn(false);

        $response = $this->instance->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_500, $response->getStatusCode());
        $expected = json_encode(['error' => "I'm sorry, something went wrong!"], JSON_HEX_APOS);
        $this->assertEquals($expected, $response->getBody()->getContents());
    }

    protected function setUp()
    {
        $this->container = ContainerMock::getinstance();
        ContainerMock::$instances[MutantService::class]->reveal();
        $this->instance = new MutantHandler(
            $this->container->get(MutantService::class),
            $this->container->get(MutantDNAValidator::class)
        );
    }
}
