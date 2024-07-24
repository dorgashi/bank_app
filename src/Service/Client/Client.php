<?php

declare(strict_types=1);

namespace BankApp\Service\Client;

use BankApp\Service\Exception\InvalidClientTypeException;

class Client
{
    /**
     * @var string
     */
    public const DEPOSIT_COMMISSION_FEE_PROPORTION = '0.0003';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $clientId, string $type)
    {
        $this->id = $clientId;
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function createClientFromType(string $clientId, string $clientType): Client {
        if (!ClientType::isValidClientType($clientType)) {
            throw new InvalidClientTypeException($clientType);
        }

        if ($clientType == ClientType::PRIVATE) {
            return new PrivateClient($clientId);
        } else if ($clientType == ClientType::BUSINESS) {
            return new BusinessClient($clientId);
        }
    }
}
