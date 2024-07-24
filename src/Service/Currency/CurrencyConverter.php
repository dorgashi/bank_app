<?php

declare(strict_types=1);

namespace BankApp\Service\Currency;

use BankApp\Service\Exception\InvalidCurrencyException;
use BankApp\Service\Http\HttpRequest;
use BankApp\Service\Math\Math;

class CurrencyConverter
{
    /**
     * @var string
     */
    public const API_URL = 'https://api.exchangerate-api.com/v4/latest/';

    /**
     * @var array
     */
    public const PRESET_RATES = [
        'EUR' => [
            'rates' => [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
        ],
        'USD' => [
            'rates' => [
                'EUR' => '0.86979211968339566843',
                'JPY' => '112.66417326259024093173',
            ],
        ],
        'JPY' => [
            'rates' => [
                'EUR' => '0.00772021925422682004',
                'USD' => '0.00671498586955450990',
            ],
        ],
    ];

    /**
     * @var bool
     */
    private static $usePresetRates = false;

    public static function convert(string $amount, string $fromCurrency, string $toCurrency): string
    {
        if (!Currency::isValidCurrency($fromCurrency)) {
            throw new InvalidCurrencyException($fromCurrency);
        } elseif (!Currency::isValidCurrency($toCurrency)) {
            throw new InvalidCurrencyException($toCurrency);
        }

        if (CurrencyConverter::$usePresetRates) {
            return Math::mul($amount, CurrencyConverter::PRESET_RATES[$fromCurrency]['rates'][$toCurrency], Currency::getDecimalPlaces($toCurrency));
        }

        $httpRequest = new HttpRequest(CurrencyConverter::API_URL.$fromCurrency);
        $apiResponse = $httpRequest->get();

        return Math::mul($amount, strval($apiResponse['rates'][$toCurrency]), Currency::getDecimalPlaces($toCurrency));
    }

    /* Global flag to not call API but instead use predefined rates, for testing purposes
    */
    public static function setUsePresetRates(bool $yesOrNo)
    {
        CurrencyConverter::$usePresetRates = $yesOrNo;
    }
}
