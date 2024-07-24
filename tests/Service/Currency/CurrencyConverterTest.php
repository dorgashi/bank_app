<?php

declare(strict_types=1);

namespace BankApp\Service\Tests\Currency;

use PHPUnit\Framework\TestCase;
use BankApp\Service\Currency\Currency;
use BankApp\Service\Currency\CurrencyConverter;

use BankApp\Service\Exception\InvalidCurrencyException;
use BankApp\Service\Exception\ExceptionCode;

class CurrencyTest extends TestCase
{
    public function setUp()
    {
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param string $invalidCurrency
     * 
     * @dataProvider dataProviderForInvalidCurrencyExceptionTesting
     */
    public function testInvalidCurrencyException(string $fromCurrency, string $toCurrency, string $invalidCurrency) : void
    {
        $this->expectException(InvalidCurrencyException::class);
        $this->expectExceptionCode(ExceptionCode::INVALID_CURRENCY);
        $this->expectExceptionMessage(sprintf("Unknown currency \"$invalidCurrency\", available currencies: %s", join(", ", array_keys(Currency::DECIMAL_PLACES))));

        CurrencyConverter::convert("1", $fromCurrency, $toCurrency);
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param string $expectation
     * 
     * @dataProvider dataProviderForMockConversionTesting
     */
    public function testMockConversion(string $amount, string $fromCurrency, string $toCurrency, string $expectation) : void
    {
        /* Forces CurrencyConverter to use preset rates and not make API calls
         * Normally, this would be the time to mock, but since using the preset rates is inbuilt functionality, we just use that instead
        */
        CurrencyConverter::setUsePresetRates(true);
        $this->assertEquals(
            CurrencyConverter::convert($amount, $fromCurrency, $toCurrency),
            $expectation
        );
    }

    public function dataProviderForInvalidCurrencyExceptionTesting(): array
    {
        return [
            'check valid from currency and invalid to currency' => ['EUR', 'INVALID_CURRENCY_123', 'INVALID_CURRENCY_123'],
            'check invalid from currency and invalid to currency' => ['INVALID_CURRENCY_123', 'USD', 'INVALID_CURRENCY_123'],
            'check both invalid currencies' => ['INVALID_CURRENCY_123', 'INVALID_CURRENCY_123', 'INVALID_CURRENCY_123']
        ];
    }

    public function dataProviderForMockConversionTesting(): array
    {
        return [
            'convert from EUR to JPY' => ['82.341', 'EUR', 'JPY', '10665'],
            'convert from EUR to USD' => ['82.341', 'EUR', 'USD', '94.66'],
            'convert from USD to EUR' => ['82.341', 'USD', 'EUR', '71.61'],
            'convert from USD to JPY' => ['82.341', 'USD', 'JPY', '9276'],
            'convert from JPY to EUR' => ['82.341', 'JPY', 'EUR', '0.63'],
            'convert from JPY to USD' => ['82.341', 'JPY', 'USD', '0.55']
        ];
    }
}
