<?php

declare(strict_types=1);

namespace App\Mutant\Handler;

use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

/**
 * Class MutantHandler
 * @package App\Mutant\Handler
 */
class MutantHandler implements RequestHandlerInterface
{
    /**
     * @var MutantService|null
     */
    private $mutantService = null;


    /**
     * @var MutantDNAValidator|null
     */
    private $mutantDNAValidator = null;

    /**
     * MutantHandler constructor.
     * @param MutantService $mutantService
     * @param MutantDNAValidator $mutantDNAValidator
     */
    public function __construct(
        MutantService $mutantService,
        MutantDNAValidator $mutantDNAValidator
    )
    {
        $this->mutantService = $mutantService;
        $this->mutantDNAValidator = $mutantDNAValidator;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $rawData = $request->getBody()->getContents();
        $parsedBody = json_decode($rawData)->dna;
        $isMutante = $this->mutantDNAValidator->isValid($parsedBody);

        if ($isMutante) {
            return new JsonResponse(
                [
                    'isMutant' => $isMutante
                ],
                Response::STATUS_CODE_200
            );
        }

        return new JsonResponse(
            [
                'isMutant' => $isMutante
            ],
            Response::STATUS_CODE_403
        );
    }


}
