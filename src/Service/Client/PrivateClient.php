<?php

declare(strict_types=1);

namespace BankApp\Service\Client;

class PrivateClient extends Client
{
    /**
     * @var string
     */
    public const WITHDRAW_COMMISSION_FEE_PROPORTION = '0.003';

    /**
     * @var string
     */
    public const WITHDRAW_WEEKLY_FREE_MAX_AMOUNT_EUROS = '1000';

    /**
     * @var string
     */
    public const WITHDRAW_WEEKLY_FREE_MAX_OPERATIONS = '3';

    public function __construct(string $clientId)
    {
        parent::__construct($clientId, ClientType::PRIVATE);
    }
}
