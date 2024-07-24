<?php

declare(strict_types=1);

namespace BankApp\Tests\Service\Client;

use PHPUnit\Framework\TestCase;

use BankApp\Service\Client\Client;
use BankApp\Service\Client\ClientType;
use BankApp\Service\Exception\InvalidClientTypeException;
use BankApp\Service\Exception\ExceptionCode;

class ClientTest extends TestCase
{
    public function setUp()
    {
    }

    /**
     * @param string $number
     * @param int @decimalPlaces
     * @param string $expectation
     *
     * @dataProvider dataProviderForCreateClientFromTypeTesting
     */
    public function testCreateClientFromType(string $clientType)
    {
        $client = Client::createClientFromType("1", $clientType);

        $this->assertEquals(
            $clientType,
            $client->getType()
        );
    }

    public function testInvalidClientTypeException() {
        $falseClientType = "BAD";

        $this->expectException(InvalidClientTypeException::class);
        $this->expectExceptionCode(ExceptionCode::INVALID_CLIENT_TYPE);
        $this->expectExceptionMessage(sprintf("Invalid client type: \"$falseClientType\", available client types: %s", join(', ', ClientType::TYPE_ARRAY)));

        Client::createClientFromType("1", $falseClientType);
    }

    public function dataProviderForCreateClientFromTypeTesting(): array
    {
        return [
            'create business client' => ['BUSINESS'],
            'create private client' => ['BUSINESS']
        ];
    }
}
