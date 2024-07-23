<?php

declare(strict_types=1);

namespace BankApp\Exception;

use BankApp\Exception\ExceptionCode;

class InvalidCsvStructureException extends \Exception
{
    public function __construct(string $filePath, $failedAtRow, $failedAtColumn, $message) {
        $message = sprintf("Failed while parsing CSV file at: \"%s\", row %s column %s, %s", $filePath, $failedAtRow, $failedAtColumn, $message);
        parent::__construct($message, ExceptionCode::INVALID_FILE_FORMAT);
        $this->message = "$message";
        $this->code = ExceptionCode::INVALID_FILE_FORMAT;
    }
}
