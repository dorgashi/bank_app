<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

use BankApp\Service\Client\ClientType;

class InvalidClientTypeException extends \Exception
{
    public function __construct(string $providedClientType)
    {
        $message = sprintf("Invalid client type: \"$providedClientType\", available client types: %s", join(', ', ClientType::TYPE_ARRAY));
        parent::__construct($message, ExceptionCode::INVALID_CLIENT_TYPE);
        $this->message = "$message";
        $this->code = ExceptionCode::INVALID_CLIENT_TYPE;
    }
}
