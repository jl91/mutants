<?php

declare(strict_types=1);

namespace App\Mutant\DNA;

/**
 * Class DNAEntity
 * @package App\Mutant\DNA
 */
class DNAEntity
{
    /**
     * @var string
     */
    public $data = '';
    /**
     * @var int
     */
    public $columns = 0;
    /**
     * @var int
     */
    public $rows = 0;
    /**
     * @var bool
     */
    public $isMutant = false;
}
