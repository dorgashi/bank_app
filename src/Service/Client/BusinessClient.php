<?php

declare(strict_types=1);

namespace BankApp\Service\Client;

class BusinessClient extends Client
{
    /**
     * @var string
     */
    public const WITHDRAW_COMMISSION_FEE_PROPORTION = '0.005';

    public function __construct(string $clientId)
    {
        parent::__construct($clientId, ClientType::BUSINESS);
    }
}
