<?php

declare(strict_types=1);

namespace App\Mutant\MutantStats;

use App\Mutant\Service\MutantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\Response;

/**
 * Class MutantStatsHandler
 * @package App\Mutant\MutantStats
 */
class MutantStatsHandler implements RequestHandlerInterface
{
    /**
     * @var MutantService|null
     */
    private $mutantService = null;

    /**
     * MutantStatsHandler constructor.
     * @param MutantService $mutantService
     */
    public function __construct(MutantService $mutantService)
    {
        $this->mutantService = $mutantService;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $stats = $this->mutantService
                ->fetchStats();
            return new JsonResponse($stats, Response::STATUS_CODE_200);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'error' => 'I\'m sorry, something went wrong!',
                    'message' => $e->getMessage()
                ],
                Response::STATUS_CODE_500
            );
        }
    }
}
