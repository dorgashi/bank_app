<?php

declare(strict_types=1);

namespace BankApp\Service\Operation;

use BankApp\Service\Client\Client;
use BankApp\Service\Currency\Currency;
use BankApp\Service\Currency\CurrencyConverter;
use BankApp\Service\Date\Date;
use BankApp\Service\Exception\InvalidCurrencyException;
use BankApp\Service\Exception\OperationInvalidTypeException;
use BankApp\Service\Math\Math;
use DateTime;

abstract class OperationType
{
    const DEPOSIT = 'DEPOSIT';
    const WITHDRAW = 'WITHDRAW';

    public static function isValidOperationType(string $operationType): bool
    {
        return defined("BankApp\Service\Operation\OperationType::$operationType");
    }
}

class Operation
{
    const DATE_FORMAT = 'Y-m-d';
    const DEPOSIT_COMMISSION_FEE_PROPORTIONAGE = 0.03;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $dateIsoWeek;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $amountInEur;

    /**
     * @var string
     */
    private $commissionFee = '0';

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $currencyDecimalPlaces;

    /**
     * @var bool
     */
    private $completed;

    private function __construct(Client $client, string $type, DateTime $date, string $amount, string $currency)
    {
        if (!Currency::isValidCurrency($currency)) {
            throw new InvalidCurrencyException($currency);
        }

        $this->client = $client;
        $this->type = $type;
        $this->date = $date;
        $this->dateIsoWeek = Date::getIsoWeek($date);
        $this->amount = $amount;
        $this->currency = $currency;
        $this->currencyDecimalPlaces = Currency::getDecimalPlaces($currency);

        if ($currency !== Currency::EUR) {
            $this->amountInEur = CurrencyConverter::convert($amount, $currency, Currency::EUR);
        } else {
            $this->amountInEur = $this->amount;
        }

        $this->completed = false;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getIsoWeek(): string
    {
        return $this->dateIsoWeek;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getAmountInEur(): string
    {
        return $this->amountInEur;
    }

    public function setCommissionFee(string $commissionFee): void
    {
        $this->commissionFee = $commissionFee;
    }

    public function getCommissionFee(): string
    {
        return $this->commissionFee;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCurrencyDecimalPlaces(): int
    {
        return $this->currencyDecimalPlaces;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function roundUpCommissionFee(string $fee): string
    {
        return Math::roundUpAtDecimalPlace($fee, (int) $this->getCurrencyDecimalPlaces());
    }

    public static function createDepositOperation(Client $client, DateTime $date, string $amount, string $currency): Operation
    {
        return new Operation($client, OperationType::DEPOSIT, $date, $amount, $currency);
    }

    public static function createWithdrawOperation(Client $client, DateTime $date, string $amount, string $currency): Operation
    {
        return new Operation($client, OperationType::WITHDRAW, $date, $amount, $currency);
    }

    public static function createOperation(Client $client, string $operationType, DateTime $date, string $amount, string $currency): Operation
    {
        if (!OperationType::isValidOperationType($operationType)) {
            throw new OperationInvalidTypeException($operationType);
        }

        if ($operationType == OperationType::DEPOSIT) {
            return Operation::createDepositOperation($client, $date, $amount, $currency);
        } else {
            return Operation::createWithdrawOperation($client, $date, $amount, $currency);
        }
    }
}
