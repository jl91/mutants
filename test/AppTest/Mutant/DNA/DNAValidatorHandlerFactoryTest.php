<?php

namespace AppTest\Mutant\DNA;

use App\Mutant\DNA\DNAValidatorHandler;
use App\Mutant\DNA\DNAValidatorHandlerFactory;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;

/**
 * Class DNAValidatorHandlerFactoryTest
 * @package AppTest\Mutant\DNA
 * @group DNAValidatorHandlerFactoryTest
 */

class DNAValidatorHandlerFactoryTest extends TestCase
{

    public function testFactoryService()
    {
        $factory = new DNAValidatorHandlerFactory();

        $this->assertInstanceOf(DNAValidatorHandlerFactory::class, $factory);

        $actual = $factory(ContainerMock::getinstance());

        $this->assertInstanceOf(DNAValidatorHandler::class, $actual);
    }

}
