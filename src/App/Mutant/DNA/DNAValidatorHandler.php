<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

/**
 * Class DNAValidatorHandler
 * @package App\Mutant\DNA
 */
class DNAValidatorHandler implements MiddlewareInterface
{
    /**
     * @var DNAValidator|null
     */
    private $validator = null;

    /**
     * DNAValidatorHandler constructor.
     * @param DNAValidator $validator
     */
    public function __construct(DNAValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $rawData = $request->getBody()->getContents();
        $parsedData = json_decode($rawData)->dna;

        if (!$this->validator->isValid($parsedData)) {
            return new JsonResponse(
                [
                    'message' => $this->validator->getMessages()
                ],
                Response::STATUS_CODE_422
            );
        }

        return $handler->handle($request);
    }
}

