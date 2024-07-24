<?php

declare(strict_types=1);

namespace BankApp\Service\Currency;

use BankApp\Service\Exception\InvalidCurrencyException;

abstract class Currency
{
    /* Would use enums if PHP >= 8.1 */

    /**
     * @var string
     */
    public const EUR = 'EUR';

    /**
     * @var string
     */
    public const USD = 'USD';

    /**
     * @var string
     */
    public const JPY = 'JPY';

    /**
     * @var array
     */
    public const CURRENCY_ARRAY = [
        Currency::EUR,
        Currency::USD,
        Currency::JPY
    ];

    /**
     * @var array
     */
    const DECIMAL_PLACES = [
        CURRENCY::EUR => 2,
        CURRENCY::USD => 2,
        CURRENCY::JPY => 0,
    ];

    public static function isValidCurrency(string $currency)
    {
        return in_array($currency, Currency::CURRENCY_ARRAY);
    }

    public static function getDecimalPlaces(string $currency): int
    {
        if (!array_key_exists($currency, Currency::DECIMAL_PLACES)) {
            throw new InvalidCurrencyException($currency);
        }

        return Currency::DECIMAL_PLACES[$currency];
    }
}
