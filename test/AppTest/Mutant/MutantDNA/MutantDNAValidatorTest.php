<?php

namespace AppTest\Mutant\MutantDNA;

use App\Mutant\MutantDNA\MutantDNAValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class MutantDNAValidatorTest
 * @package AppTest\Mutant\MutantDNA
 * @group MutantDNAValidatorTest
 */
class MutantDNAValidatorTest extends TestCase
{
    /**
     * @var MutantDNAValidator
     */
    private $instance = null;

    public function setUp()
    {
        $this->instance = new MutantDNAValidator();
    }

    public function dnaDataProvider()
    {
        return [
            [
                'dna' => [
                    'ACT',
                    'ACT',
                    'ACT',
                    'ACT',
                    'ACT',
                    'ACT',
                ],
                'expected' => false,
                'expectedMessage' => 'Input nitrogenous bases has no minimum size to be from a mutant'
            ],
            [
                'dna' => [
                    'AAAAACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                ],
                'expected' => true,
                'expectedMessage' => ''
            ],
            [
                'dna' => [
                    "ATGCGA",
                    "CAGTGC",
                    "TTATGT",
                    "AGAAGG",
                    "CCCCTA",
                    "TCACTG"
                ],
                'expected' => true,
                'expectedMessage' => ''
            ],
            [
                'dna' => [
                    "ATGCGA",
                    "CAGTGC",
                    "TTATGT",
                    "AGAGGG",
                    "CCCGTA",
                    "TCACTG"
                ],
                'expected' => false,
                'expectedMessage' => ''
            ],
        ];

    }

    /**
     * @dataProvider dnaDataProvider
     */
    public function testIsMutant($dna, $expected)
    {

        $actual = $this->instance->isMutant($dna);
        $this->assertEquals($expected, $actual);

    }

    /**
     * @dataProvider dnaDataProvider
     */
    public function testIsValid($dna, $expected)
    {
        $actual = $this->instance->isValid($dna);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dnaDataProvider
     */
    public function testGetMessages($dna, $expected, $expectedMessage)
    {
        $this->instance->isValid($dna);
        $actual = $this->instance->getMessages();
        $this->assertEquals($expectedMessage, $actual);
    }
}
