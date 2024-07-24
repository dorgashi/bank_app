<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

class FileNotFoundException extends \Exception
{
    public function __construct(string $filePath)
    {
        $message = "File not found: \"$filePath\"";
        parent::__construct($message, ExceptionCode::FILE_NOT_FOUND);
        $this->message = "$message";
        $this->code = ExceptionCode::FILE_NOT_FOUND;
    }
}
