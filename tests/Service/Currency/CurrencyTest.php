<?php

declare(strict_types=1);

namespace BankApp\Service\Tests\Service\Math;

use PHPUnit\Framework\TestCase;
use BankApp\Service\Currency\Currency;

use BankApp\Service\Exception\InvalidCurrencyException;
use BankApp\Service\Exception\ExceptionCode;

class CurrencyTest extends TestCase
{
    public function setUp()
    {
    }

    /**
     * @param string $currency
     * @param bool $expectation
     *
     * @dataProvider dataProviderForIsValidCurrencyTesting
     */
    public function testIsValidCurrency(string $currency, bool $expectation)
    {
        $this->assertEquals(
            $expectation,
            Currency::isValidCurrency($currency)
        );
    }

        /**
     * @param string $currency
     * @param int $expectation
     *
     * @dataProvider dataProviderForGetDecimalPlacesTesting
     */
    public function testGetDecimalPlaces(string $currency, int $expectation)
    {
        $this->assertEquals(
            $expectation,
            Currency::getDecimalPlaces($currency)
        );
    }

        /**
     * Expect InvalidCurrencyException if given a currency that doesn't exist as a constant in Currency class
     */
    public function testInvalidCurrencyException()
    {
        $this->expectException(InvalidCurrencyException::class);
        $this->expectExceptionCode(ExceptionCode::INVALID_CURRENCY);
        $this->expectExceptionMessage(sprintf("Unknown currency \"INVALID_CURRENCY_123\", available currencies: %s", join(", ", array_keys(Currency::DECIMAL_PLACES))));

        Currency::getDecimalPlaces("INVALID_CURRENCY_123");
    }

    public function dataProviderForIsValidCurrencyTesting(): array
    {
        return [
            'check EUR' => ['EUR', true],
            'check USD' => ['USD', true],
            'check SOL' => ['SOL', false],
            'check JPY' => ['JPY', true]
        ];
    }

    public function dataProviderForGetDecimalPlacesTesting(): array
    {
        return [
            'check EUR' => ['EUR', 2],
            'check USD' => ['USD', 2],
            'check JPY' => ['JPY', 0]
        ];
    }
}
