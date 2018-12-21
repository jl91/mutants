<?php

declare(strict_types=1);

namespace App\Mutant\MutantStats;

/**
 * Class MutantStatsEntity
 * @package App\Mutant\MutantStats
 */
class MutantStatsEntity
{
    /**
     * @var Quantidade de dnas humanos registrados
     */
    public $count_human_dna;
    /**
     * @var Quantidade de dnas mutantes registrados
     */
    public $count_mutant_dna;
    /**
     * @var Ratio entre quantidade de dnas humanos e mutantes
     */
    public $ratio;
}
