<?php

namespace AppTest\Mutant\Handler;

use App\Mutant\Handler\MutantHandler;
use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
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

    /** @var MutantService|ObjectProphecy */
    protected $service;

    /** @var StreamInterface|ObjectProphecy */
    protected $stream;

    /** @var ServerRequestInterface|ObjectProphecy */
    protected $request;

    /** @var MutantDNAValidator|ObjectProphecy */
    protected $mutantValidator;

    /**
     * @var
     */
    protected $instance;

    public function test__construct()
    {
        $actual = $this->instance;
        $this->assertInstanceOf(MutantHandler::class, $actual);
    }

    public function testHandle()
    {
        $this->setRequestData();
        $this->service->persist()->willReturn(true);
        $this->mutantValidator->isValid()->willReturn(true);

        $response = $this->instance->handle($this->request->reveal());
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());


        $this->setRequestData();
        $response = $this->instance->handle($this->request->reveal());
        $this->mutantValidator->isValid()->willReturn(false);
        $this->service->persist()->willReturn(false);
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

        $this->stream->getContents()->willReturn(json_encode($requestContent));
        $this->request->getBody()->willReturn($this->stream);
        return $this->request->reveal();
    }

    protected function setUp()
    {
        $this->stream = $this->prophesize(StreamInterface::class);
        $this->request = $this->prophesize(ServerRequestInterface::class);
        $this->service = $this->prophesize(MutantService::class);
        $this->mutantValidator = $this->prophesize(MutantDNAValidator::class);

        $this->instance = new MutantHandler(
            $this->service->reveal(),
            $this->mutantValidator->reveal()
        );
    }
}
