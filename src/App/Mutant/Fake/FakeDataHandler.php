<?php

declare(strict_types=1);

namespace App\Mutant\Fake;

use App\Mutant\MutantDNA\MutantDNAValidator;
use App\Mutant\Service\MutantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

class FakeDataHandler implements RequestHandlerInterface
{
    private $mutantService = null;
    private $mutantDNAValidator = null;

    public function __construct(
        MutantService $mutantService,
        MutantDNAValidator $mutantDNAValidator
    )
    {
        $this->mutantService = $mutantService;
        $this->mutantDNAValidator = $mutantDNAValidator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $quantity = $request->getAttribute('quantity', 100);

        $humans = 0;
        $mutants = 0;
        for ($i = 0; $i <= $quantity; $i++) {
            $data = $this->generateData();

            $isMutant = $this->mutantDNAValidator
                ->isValid($data);

            $this->mutantService
                ->persist($data, $isMutant);
            $humans += !$isMutant ? 1 : 0;
            $mutants += $isMutant ? 1 : 0;
        }

        return new JsonResponse(
            [
                'humans' => $humans,
                'mutants' => $mutants,
            ],
            Response::STATUS_CODE_201
        );

    }

    private function generateData(): array
    {
        return [
            str_shuffle($this->generateRandomString()),
            str_shuffle($this->generateRandomString()),
            str_shuffle($this->generateRandomString()),
            str_shuffle($this->generateRandomString()),
            str_shuffle($this->generateRandomString()),
            str_shuffle($this->generateRandomString()),
        ];
    }

    function generateRandomString()
    {
        $characters = implode('', [
                MutantDNAValidator::ADENINE_REQUENCE,
                MutantDNAValidator::CYTOSINE_REQUENCE,
                MutantDNAValidator::GUANINE_REQUENCE,
                MutantDNAValidator::THYMINE_REQUENCE
            ]
        );

        $characters = str_shuffle($characters);
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
