<?php

declare(strict_types=1);

namespace BankApp\Exception;

use BankApp\Exception\ExceptionCode;

class FileNotFoundException extends \Exception
{
    public function __construct(string $filePath) {
        $message = sprintf("File not found: \"%s\"", $filePath);
        parent::__construct($message, 404);
        $this->message = "$message";
        $this->code = ExceptionCode::FILE_NOT_FOUND;
    }
}
