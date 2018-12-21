<?php

namespace AppTest\Mutant\DNA;

use App\Mutant\DNA\DNAValidator;
use App\Mutant\DNA\DNAValidatorHandler;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class DNAValidatorHandlerTest
 * @package AppTest\Mutant\DNA
 * @group DNAValidatorHandlerTest
 */
class DNAValidatorHandlerTest extends TestCase
{
    const DATA = [
        "ATGCGA",
        "CAGTGC",
        "TTATGT",
        "AGAAGG",
        "CCCCTA",
        "TCACTG"
    ];
    /**
     * @var DNAValidatorHandler
     */
    private $instance;
    private $container;

    public function test__construct()
    {

        $this->assertInstanceOf(DNAValidatorHandler::class, $this->instance);
    }

    public function testProcess()
    {

        $this->setRequestData();
        $request = $this->container->get(ServerRequestInterface::class);
        $requestHandle = $this->container->get(RequestHandlerInterface::class);


        ContainerMock::$instances[DNAValidator::class]
            ->isValid(self::DATA)
            ->willReturn(true);

        ContainerMock::$instances[ResponseInterface::class]
            ->getStatusCode()
            ->willReturn(\Zend\Http\Response::STATUS_CODE_200);

        ContainerMock::$instances[RequestHandlerInterface::class]
            ->handle($request)
            ->willReturn($this->container->get(ResponseInterface::class));

        $response = $this->instance->process($request, $requestHandle);

        $this->assertEquals(\Zend\Http\Response::STATUS_CODE_200, $response->getStatusCode());
    }

    public function testProcess422()
    {
        $this->setRequestData();
        $request = $this->container->get(ServerRequestInterface::class);
        $requestHandle = $this->container->get(RequestHandlerInterface::class);


        ContainerMock::$instances[DNAValidator::class]
            ->isValid(self::DATA)
            ->willReturn(false);

        ContainerMock::$instances[DNAValidator::class]
            ->getMessages()
            ->willReturn('');

        $response = $this->instance->process($request, $requestHandle);

        $this->assertEquals(\Zend\Http\Response::STATUS_CODE_422, $response->getStatusCode());

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

    public function setUp()
    {
        $this->container = ContainerMock::getinstance();
        $this->instance = new DNAValidatorHandler($this->container->get(DNAValidator::class));
    }
}
