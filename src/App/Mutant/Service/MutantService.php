<?php

declare(strict_types=1);

namespace App\Mutant\Service;

class MutantService
{
    const MAGNETO_NAMESPACE = 'magneto.mutant';
    private $mongo = null;

    public function __construct(\MongoDB\Driver\Manager $mongo)
    {
        $this->mongo = $mongo;
    }

    public function persist($data)
    {
//        $query = new
        $this->mongo->executeQuery(self::MAGNETO_NAMESPACE, $query);

    }

}
