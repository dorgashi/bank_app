<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

class InvalidFileFormatException extends \Exception
{
    public function __construct(string $filePath, string $expectedFileFormat)
    {
        $message = "Invalid file format provided \"$filePath\". Expected format: $expectedFileFormat";
        parent::__construct($message, ExceptionCode::INVALID_FILE_FORMAT);
        $this->message = "$message";
        $this->code = ExceptionCode::INVALID_FILE_FORMAT;
    }
}
