<?php

namespace AppTest\Mutant\Service;

use App\Mutant\Service\MutantService;
use App\Mutant\Service\MutantServiceFactory;
use AppTest\Helpers\ContainerMock;
use PHPUnit\Framework\TestCase;

/**
 * Class MutantServiceFactoryTest
 * @package AppTest\Mutant\Service
 * @group MutantServiceFactoryTest
 * @codeCoverageIgnore
 * Ignorado pq a classe do provida do driver do mongo é final,  é possível aplicar reflection em final classes
 * con isso não é possivel criar um mock dele.
 */
class MutantServiceFactoryTest extends TestCase
{

    public function testFactoryService()
    {
        $factory = new MutantServiceFactory();

        $this->assertInstanceOf(MutantServiceFactory::class, $factory);

        $actual = $factory(ContainerMock::getinstance());

        $this->assertInstanceOf(MutantService::class, $actual);
    }
}
