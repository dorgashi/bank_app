<?php

declare(strict_types=1);

namespace BankApp\Tests\Service\File;

use PHPUnit\Framework\TestCase;
use BankApp\Service\Ledger\Ledger;
use BankApp\Service\Operation\Operation;
use BankApp\Service\Currency\CurrencyConverter;

class OperationTest extends TestCase
{
    /**
     * @var array
     */
    private $testOperationData;

    /**
     * @var array
     */
    private $expectedCommissionFees;

    public function setUp(): void
    {
        $testsDir = dirname(__DIR__, 2);
        $this->testOperationData = $this->loadJsonData("$testsDir/Data/CsvParserTestData.json");

        $this->expectedCommissionFees = [
            "0.60",
            "3.00",
            "0.00",
            "0.06",
            "1.50",
            "0",
            "0.70",
            "0.30",
            "0.30",
            "3.00",
            "0.00",
            "0.00",
            "8612"
        ];
    }

    public function loadJsonData(string $path): array {
        $jsonFile = file_get_contents($path);

        $jsonFile = json_decode($jsonFile, true);

        for ($i = 0; $i < count($jsonFile); $i++) {
            $jsonFile[$i]['operation_date'] = \DateTime::createFromFormat("Y-m-d", $jsonFile[$i]['operation_date']);
        }

        return $jsonFile;
    }

    /**
     *
     */
    public function testOperationCommissionFeeGeneration()
    {
        /* Forces CurrencyConverter to use preset rates and not make API calls
         * Normally, this would be the time to mock, but since using the preset rates is inbuilt functionality, we just use that instead
        */
        CurrencyConverter::setUsePresetRates(true);

        /* Simulated in-memory database, would be a mock in real app */
        $ledger = new Ledger();

        for ($i = 0; $i < count($this->testOperationData); $i++) {
            $row = $this->testOperationData[$i];
            $operationDate = $row['operation_date'];
            $clientId = $row['user_id'];
            $clientType = strtoupper($row['client_type']);
            $operationType = strtoupper($row['operation_type']);
            $operationAmount = $row['operation_amount'];
            $operationCurrency = $row['operation_currency'];

            $client = $ledger->getOrCreateClient($clientId, $clientType);

            $operation = Operation::createOperation($client, $operationType, $operationDate, $operationAmount, $operationCurrency);

            $ledger->storeOperation($operation);

            $this->assertEquals(
                $this->expectedCommissionFees[$i],
                $operation->getCommissionFee()
            ); 
        }
    }
}
