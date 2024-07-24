<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

class InvalidCsvStructureException extends \Exception
{
    public function __construct(string $filePath, $failedAtRow, $failedAtColumn, $message)
    {
        $message = "Failed while parsing CSV file at: \"$filePath\", row $failedAtRow column $failedAtColumn, $message";
        parent::__construct($message, ExceptionCode::INVALID_CSV_STRUCTURE);
        $this->message = "$message";
        $this->code = ExceptionCode::INVALID_CSV_STRUCTURE;
    }
}
