<?php

declare(strict_types=1);

namespace App\Mutant;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

class Mutant implements RequestHandlerInterface
{
    /**
     * @var null
     */
    private $mutantService = null;

    public function __construct(MutantService $mutantService)
    {
        $this->mutantService = $mutantService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $isMutante = $this->mutantService
            ->isMutant([]);

        if ($isMutante) {
            return new JsonResponse([
                'isMutant' => true
            ], Response::STATUS_CODE_200);
        }

        return new JsonResponse([
            'isMutant' => false
        ], Response::STATUS_CODE_403);
    }
}
