<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

use BankApp\Service\Currency\Currency;

class InvalidCurrencyException extends \Exception
{
    public function __construct(string $inputCurrency)
    {
        $message = sprintf("Unknown currency \"$inputCurrency\", available currencies: %s", join(', ', array_keys(Currency::DECIMAL_PLACES)));
        parent::__construct($message, ExceptionCode::INVALID_CURRENCY);
        $this->message = "$message";
        $this->code = ExceptionCode::INVALID_CURRENCY;
    }
}
