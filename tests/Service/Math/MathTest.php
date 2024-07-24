<?php

declare(strict_types=1);

namespace BankApp\Tests\Service\Math;

use PHPUnit\Framework\TestCase;
use BankApp\Service\Math\Math;

class MathTest extends TestCase
{
    /**
     * @var int
     */
    private $scale;

    public function setUp()
    {
        $this->scale = 2;
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Math::add($leftOperand, $rightOperand, $this->scale)
        );
    }

        /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForSubTesting
     */
    public function testSub(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Math::sub($leftOperand, $rightOperand, $this->scale)
        );
    }

            /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForMulTesting
     */
    public function testMul(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Math::mul($leftOperand, $rightOperand, $this->scale)
        );
    }

            /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForDivTesting
     */
    public function testDiv(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Math::div($leftOperand, $rightOperand, $this->scale)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForPowTesting
     */
    public function testPow(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Math::pow($leftOperand, $rightOperand, $this->scale)
        );
    }

    /**
     * @param string $number
     * @param int @decimalPlaces
     * @param string $expectation
     *
     * @dataProvider dataProviderForRoundUpAtDecimalPlacesTesting
     */
    public function testRoundUpAtDecimalPlaces(string $number, int $decimalPlaces, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Math::roundUpAtDecimalPlace($number, $decimalPlaces)
        );
    }

    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', '3'],
            'add negative number to a positive' => ['-1', '2', '1'],
            'add natural number to a float' => ['1', '1.05123', '2.05'],
            'add float to a negative float' => ['1.023', '-0.199', '0.82']
        ];
    }

    public function dataProviderForSubTesting(): array
    {
        return [
            'subtract 2 natural numbers' => ['12', '6', '6'],
            'subtract positive number from negative' => ['-5', '4', '-9'],
            'subtract natural number from a float' => ['5.294', '3', '2.29'],
            'subtract negative float from a negative float' => ['-0.943', '-1.223', '0.28']
        ];
    }

    public function dataProviderForMulTesting(): array
    {
        return [
            'multiply 2 natural numbers' => ['12', '6', '72'],
            'multiply positive number with negative' => ['-5', '4', '-20'],
            'multiply natural number with a float' => ['5.294', '3', '15.88'],
            'multiply negative float with a negative float' => ['-0.943', '-1.223', '1.15']
        ];
    }

    public function dataProviderForDivTesting(): array
    {
        return [
            'divide 2 natural numbers' => ['12', '6', '2'],
            'divide natural number by negative' => ['-5', '4', '-1.25'],
            'divide positive float by a natural number' => ['5.294', '3', '1.76'],
            'divide negative float by a negative float' => ['-0.943', '-1.223', '0.77']
        ];
    }

    public function dataProviderForPowTesting(): array
    {
        return [
            'raise a natural number to a natural power' => ['12', '6', '2985984'],
            'raise a negative number to a positive power' => ['-5', '4', '625'],
            'raise a positive float to the power of a natural number' => ['5.294', '3', '148.37'],
            'raise a negative float to the power of a negative float' => ['-0.943', '-1.223', '-1.06']
        ];
    }

    public function dataProviderForRoundUpAtDecimalPlacesTesting(): array
    {
        return [
            'round up a natural number to 6 decimal places' => ['12', '3', '12.000000'],
            'round up a negative number to 4 decimal places' => ['-5', '4', '-5.0000'],
            'round up a positive float to 3 decimal places' => ['5.2945', '3', '5.295'],
            'round up a negative float to 2 decimal places' => ['-0.943', '2', '-0.95']
        ];
    }
}
