<?php

namespace AppTest\Mutant\MutantStats;

use App\Mutant\MutantStats\MutantStatsHandler;
use App\Mutant\MutantStats\MutantStatsHandlerFactory;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;

/**
 * Class MutantStatsHandlerFactoryTest
 * @package AppTest\Mutant\MutantStats
 * @group MutantStatsHandlerFactoryTest
 */

class MutantStatsHandlerFactoryTest extends TestCase
{
    public function testFactoryService()
    {
        $factory = new MutantStatsHandlerFactory();

        $this->assertInstanceOf(MutantStatsHandlerFactory::class, $factory);

        $actual = $factory(ContainerMock::getinstance());

        $this->assertInstanceOf(MutantStatsHandler::class, $actual);
    }
}
