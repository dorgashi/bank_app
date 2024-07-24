<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

class OperationAlreadyExecutedException extends \Exception
{
    public function __construct()
    {
        $message = 'Operation was already executed';
        parent::__construct($message, ExceptionCode::OPERATION_ALREADY_EXECUTED);
        $this->message = "$message";
        $this->code = ExceptionCode::OPERATION_ALREADY_EXECUTED;
    }
}
