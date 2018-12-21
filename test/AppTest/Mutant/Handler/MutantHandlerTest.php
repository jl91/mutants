<?php

namespace AppTest\Mutant\Handler;

use App\Mutant\Handler\MutantHandler;
use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\MockObject\MockObject;
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

    /** @var Container|ObjectProphecy */
    protected $container;

    /**
     * @var
     */
    protected $instance;
    /**
     * @var MutantService|MockObject
     */
    protected $service;

    public function test__construct()
    {
        $actual = $this->instance;
        $this->assertInstanceOf(MutantHandler::class, $actual);
    }

    public function testHandle()
    {
        $this->setRequestData();

        $response = $this->instance->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());

        $response = $this->instance->handle($this->container->get(ServerRequestInterface::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
    }

    private function setRequestData()
    {
        $requestContent = new \stdClass();
        $requestContent->dna = [
            "ATGCGA",
            "CAGTGC",
            "TTATGT",
            "AGAAGG",
            "CCCCTA",
            "TCACTG"
        ];

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
        $this->service->method('persist')->willThrowException(new \Exception());
        $response = $this->instance->handle($this->container->get(ServerRequestInterface::class));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_500, $response->getStatusCode());
    }

    protected function setUp()
    {
        $this->container = ContainerMock::getinstance();

        ContainerMock::$instances[MutantService::class]->reveal();

        $this->service = parent::getMockBuilder(MutantService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->instance = new MutantHandler(
            $this->service,
            new MutantDNAValidator()
        );
    }
}
