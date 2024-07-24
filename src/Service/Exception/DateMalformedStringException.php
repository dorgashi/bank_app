<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

class DateMalformedStringException extends \Exception
{
    public function __construct(string $inputDateStr, string $expectedFormat)
    {
        $message = "Failed to parse date string \"$inputDateStr\", expecting format \"$expectedFormat\"";
        parent::__construct($message, ExceptionCode::DATE_MALFORMED_STRING);
        $this->message = "$message";
        $this->code = ExceptionCode::DATE_MALFORMED_STRING;
    }
}
