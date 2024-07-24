<?php

declare(strict_types=1);

namespace BankApp\Service\Math;

class Math
{
    /**
     * @var int
     */
    public const DEFAULT_DECIMAL_PLACES = 10;

    public static function add(string $leftOperand, string $rightOperand, int $scale = Math::DEFAULT_DECIMAL_PLACES): string
    {
        return bcadd($leftOperand, $rightOperand, $scale);
    }

    public static function sub(string $leftOperand, string $rightOperand, int $scale = Math::DEFAULT_DECIMAL_PLACES): string
    {
        return bcsub($leftOperand, $rightOperand, $scale);
    }

    public static function mul(string $leftOperand, string $rightOperand, int $scale = Math::DEFAULT_DECIMAL_PLACES): string
    {
        return bcmul($leftOperand, $rightOperand, $scale);
    }

    public static function div(string $leftOperand, string $rightOperand, int $scale = Math::DEFAULT_DECIMAL_PLACES): string
    {
        return bcdiv($leftOperand, $rightOperand, $scale);
    }

    public static function pow(string $base, string $exponent, int $scale = Math::DEFAULT_DECIMAL_PLACES): string
    {
        return bcpow($base, $exponent, $scale);
    }

    /* Round up after X numbers to the right of the decimal point, e.g. $number = 0.023 with $decimalPlaces = 2 will yield 0.03 */
    public static function roundUpAtDecimalPlace(string $number, int $decimalPlaces): string
    {
        if ($decimalPlaces < 0) {
            throw new \Error('Math::roundUpAtDecimalPlace expects zero or positive decimal places');
        }

        $split = explode('.', $number);
        if (count($split) === 1) {
            return (string) $number;
        }

        $wholeNumber = $split[0];
        $sign = $number <=> 0;
        $decimalNumber = $split[1];

        /* If currency has no decimals i.e. JPY */
        if ($decimalPlaces === 0) {
            if ($decimalNumber > 0) {
                ++$wholeNumber;
            }

            return (string) $wholeNumber;
        }

        $resultDecimalNumbers = substr($decimalNumber, 0, $decimalPlaces);
        $extraDecimalNumbers = substr($decimalNumber, $decimalPlaces, strlen($decimalNumber));

        /* If the cut off decimal values are above 0, add lowest possible number with given decimal places to the result, for example if $decimalPlaces = 2 it will add +0.01 */
        if ($extraDecimalNumbers > 0) {
            if ($sign === 1) {
                return Math::add($number, Math::div('1', Math::pow('10', (string) $decimalPlaces)), $decimalPlaces);
            } else {
                return Math::sub($number, Math::div('1', Math::pow('10', (string) $decimalPlaces)), $decimalPlaces);
            }
        }

        return "$wholeNumber.$resultDecimalNumbers";
    }
}
