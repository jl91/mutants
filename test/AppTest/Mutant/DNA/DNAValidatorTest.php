<?php
declare(strict_types=1);

namespace AppTest\Mutant\DNA;


use App\Mutant\DNA\DNAValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class DNAValidatorTest
 * @package AppTest\Mutant\DNA
 * @group DNAValidatorTest
 */
class DNAValidatorTest extends TestCase
{

    public $instance;

    public function setUp()
    {
        $this->instance = new DNAValidator();
    }

    public function dnaDataProvider()
    {
        return [
            [
                'dna' => [
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                ],
                'expected' => true,
                'expectedMessage' => null
            ],
            [
                'dna' => [
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                ],
                'expected' => true,
                'expectedMessage' => null
            ],
            [
                'dna' => [
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                ],
                'expected' => true,
                'expectedMessage' => null
            ],
            [
                'dna' => [
                    'ACTGACTGACTG',
                    'ACXGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                ],
                'expected' => false,
                'expectedMessage' => 'Invalid nitrogenous bases found, it should be only A, T, C, G'
            ],
            [
                'dna' => [
                    'ACTGACTGACTG',
                    'ACTGACTGACTN',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                    'ACTGACTGACTG',
                ],
                'expected' => false,
                'expectedMessage' => 'Invalid nitrogenous bases found, it should be only A, T, C, G'
            ],
            [
                'dna' => [
                ],
                'expected' => false,
                'expectedMessage' => 'Input nitrogenous bases cannot be empty'
            ],
            [
                'dna' => null,
                'expected' => false,
                'expectedMessage' => 'Input nitrogenous bases should be a array'
            ],
        ];

    }

    /**
     * @dataProvider dnaDataProvider
     */
    public function testGetMessages($dna, $expected, $expectedMessage)
    {
        $this->instance->isValid($dna);
        $actualMessage = $this->instance->getMessages();
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @dataProvider dnaDataProvider
     */
    public function testIsValid($dna, $expected)
    {
        $actual = $this->instance->isValid($dna);
        $this->assertEquals($expected, $actual);
    }
}
