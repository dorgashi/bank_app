<?php

declare(strict_types=1);

namespace BankApp\Exception;

use BankApp\Exception\ExceptionCode;

class InvalidFileFormatException extends \Exception
{
    public function __construct(string $filePath, $expectedFileFormat) {
        $message = sprintf("Invalid file format at: \"%s\", expected file format: %s", $filePath, $expectedFileFormat);
        parent::__construct($message, ExceptionCode::INVALID_FILE_FORMAT);
        $this->message = "$message";
        $this->code = ExceptionCode::INVALID_FILE_FORMAT;
    }
}
