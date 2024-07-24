<?php

declare(strict_types=1);

namespace BankApp\Service\Ledger;

use BankApp\Service\Client\BusinessClient;
use BankApp\Service\Client\Client;
use BankApp\Service\Client\ClientType;
use BankApp\Service\Client\PrivateClient;
use BankApp\Service\Currency\Currency;
use BankApp\Service\Currency\CurrencyConverter;
use BankApp\Service\Math\Math;
use BankApp\Service\Operation\Operation;
use BankApp\Service\Operation\OperationType;

class Ledger
{
    /**
     * @var array
     */
    private $records;

    /**
     * @var array
     */
    private $clientMapping;

    /**
     * @var array
     */
    private $clientRecordsMapping;

    public function __construct()
    {
        $this->records = [];
        $this->clientMapping = [];
        $this->clientRecordsMapping = [];
    }

    public function getClients(): array
    {
        return $this->clientMapping;
    }

    public function getClient(string $id): ?Client
    {
        if (array_key_exists($id, $this->clientMapping)) {
            return $this->clientMapping[$id];
        }

        return null;
    }

    public function getOrCreateClient(string $clientId, string $clientType): Client
    {
        $client = $this->getClient($clientId);
        
        if ($client === null) {
            $client = Client::createClientFromType($clientId, $clientType);
        }

        $this->storeClientIfNotExists($client);

        return $client;
    }

    public function getAllRecords(): array
    {
        return $this->records;
    }

    public function getRecordsForClient(string $clientId): ?array
    {
        if (array_key_exists($clientId, $this->clientRecordsMapping)) {
            return $this->clientRecordsMapping[$clientId];
        }

        return null;
    }

    public function getRecordsForClientForWeek(string $clientId, \DateTime $date)
    {
        $records = $this->getRecordsForClient($clientId);
        $isoWeek = $date->format('oW');

        return array_filter($records, function (Operation $record) use ($isoWeek) {
            return $record->getIsoWeek() === $isoWeek;
        });
    }

    public function getWithdrawalsForClientForWeek(string $clientId, \DateTime $date)
    {
        $records = $this->getRecordsForClient($clientId);
        if (!$records) {
            return [];
        }

        $isoWeek = $date->format('oW');

        return array_filter($records, function (Operation $record) use ($isoWeek) {
            return $record->getType() === OperationType::WITHDRAW && $record->getIsoWeek() === $isoWeek;
        });
    }

    public function storeClientIfNotExists(Client $client): void
    {
        if (array_key_exists($client->getId(), $this->clientMapping)) {
            return;
        }

        $this->clientMapping[$client->getId()] = $client;
        $this->clientRecordsMapping[$client->getId()] = [];
    }

    public function calculateCommissionFee(Operation $operation): string
    {
        $client = $operation->getClient();

        if ($operation->getType() == OperationType::DEPOSIT) {
            return $this->getDepositCommissionFee($operation);
        } elseif ($client->getType() == ClientType::BUSINESS) {
            return $this->getBusinessClientWithdrawalCommissionFee($operation);
        }

        return $this->getPrivateClientWithdrawalCommissionFee($operation);
    }

    public function getDepositCommissionFee(Operation $operation): string
    {
        $fee = Math::mul($operation->getAmount(), Client::DEPOSIT_COMMISSION_FEE_PROPORTION);
        return $operation->roundUpCommissionFee($fee, $operation->getCurrencyDecimalPlaces());
    }

    public function getBusinessClientWithdrawalCommissionFee(Operation $operation): string
    {
        $fee = Math::mul($operation->getAmount(), BusinessClient::WITHDRAW_COMMISSION_FEE_PROPORTION);

        return $operation->roundUpCommissionFee($fee, $operation->getCurrencyDecimalPlaces());
    }

    public function getPrivateClientWithdrawalCommissionFee(Operation $operation): string
    {
        $client = $operation->getClient();

        $sameWeekWithdrawals = $this->getWithdrawalsForClientForWeek($client->getId(), $operation->getDate());
        $weekTotalWithdrawalCount = count($sameWeekWithdrawals);

        /* If withdrawn 3 or more times in the same week */
        if ($weekTotalWithdrawalCount >= PrivateClient::WITHDRAW_WEEKLY_FREE_MAX_OPERATIONS) {
            $fee = Math::mul($operation->getAmount(), PrivateClient::WITHDRAW_COMMISSION_FEE_PROPORTION);

            return $operation->roundUpCommissionFee($fee);
        }

        $weekTotalWithdrawnEur = '0';
        foreach ($sameWeekWithdrawals as $record) {
            $weekTotalWithdrawnEur = Math::add($weekTotalWithdrawnEur, $record->getAmountInEur());
        }

        /* If already over the weekly euro limit before this withdrawal */
        if ($weekTotalWithdrawnEur >= PrivateClient::WITHDRAW_WEEKLY_FREE_MAX_AMOUNT_EUROS) {
            $fee = Math::mul($operation->getAmount(), PrivateClient::WITHDRAW_COMMISSION_FEE_PROPORTION);

            return $operation->roundUpCommissionFee($fee);
        }

        /* If will go over the weekly euro limit after this withdrawal */
        $newWeekTotalWithdrawnEur = Math::add($weekTotalWithdrawnEur, $operation->getAmountInEur());
        if ($newWeekTotalWithdrawnEur >= PrivateClient::WITHDRAW_WEEKLY_FREE_MAX_AMOUNT_EUROS) {
            $differenceInEur = Math::sub($newWeekTotalWithdrawnEur, PrivateClient::WITHDRAW_WEEKLY_FREE_MAX_AMOUNT_EUROS);

            if ($operation->getCurrency() === Currency::EUR) {
                $fee = Math::mul($differenceInEur, PrivateClient::WITHDRAW_COMMISSION_FEE_PROPORTION);

                return $operation->roundUpCommissionFee($fee);
            }

            /* Change difference back to local currency to calculate commission fee */
            $differenceInLocalCurrency = CurrencyConverter::convert($differenceInEur, Currency::EUR, $operation->getCurrency());
            $fee = Math::mul($differenceInLocalCurrency, PrivateClient::WITHDRAW_COMMISSION_FEE_PROPORTION);

            return $operation->roundUpCommissionFee($fee);
        } else {
            return number_format(0, $operation->getCurrencyDecimalPlaces());
        }
    }

    public function storeOperation(Operation $operation, string $customCommissionFee = null): void
    {
        $client = $operation->getClient();

        $this->storeClientIfNotExists($client);

        if ($customCommissionFee) {
            $operation->setCommissionFee($customCommissionFee);
        } else {
            $operation->setCommissionFee($this->calculateCommissionFee($operation));
        }

        array_push($this->clientRecordsMapping[$client->getId()], $operation);
    }
}
