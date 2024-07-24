<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

class OperationInvalidTypeException extends \Exception
{
    public function __construct($inputOperation)
    {
        $message = "Operation \"$inputOperation\" not recognized. Available operations: deposit, withdraw";
        parent::__construct($message, ExceptionCode::OPERATION_INVALID_TYPE);
        $this->message = "$message";
        $this->code = ExceptionCode::OPERATION_INVALID_TYPE;
    }
}
