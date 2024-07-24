<?php

declare(strict_types=1);

namespace BankApp\Service\Client;

abstract class ClientType
{
    /* Would use enums if PHP >= 8.1 */

    /**
     * @var string
     */
    public const PRIVATE = 'PRIVATE';

    /**
     * @var string
     */
    public const BUSINESS = 'BUSINESS';

    /**
     * @var array
     */
    public const TYPE_ARRAY = [
        ClientType::PRIVATE,
        ClientType::BUSINESS
    ];

    public static function isValidClientType(string $type): bool {
        return in_array($type, ClientType::TYPE_ARRAY);
    }
}
