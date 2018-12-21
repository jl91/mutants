<?php

declare(strict_types=1);

namespace AppTest\Mutant\Handler;

use App\Mutant\Handler\MutantHandler;
use App\Mutant\Handler\MutantHandlerFactory;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;

/**
 * Class MutantHandlerFactoryTest
 * @package AppTest\Mutant\Handler
 * @group MutantHandlerFactoryTest
 */
class MutantHandlerFactoryTest extends TestCase
{

    public function testFactoryService()
    {
        $factory = new MutantHandlerFactory();

        $this->assertInstanceOf(MutantHandlerFactory::class, $factory);

        $actual = $factory(ContainerMock::getinstance());

        $this->assertInstanceOf(MutantHandler::class, $actual);
    }

}
