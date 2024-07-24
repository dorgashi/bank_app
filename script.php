<?php

require_once './vendor/autoload.php';

use BankApp\Service\File\CsvParser;
use BankApp\Service\Ledger\Ledger;
use BankApp\Service\Operation\Operation;
use BankApp\Service\Currency\CurrencyConverter;

function main(int $argc, array $argv) {
    if ($argc < 2) throw new Error("Usage is \"php script.php path/to/file.csv\"");

    $csvFilePath = $argv[1];
    $usePresetRates = $argc > 2 && $argv[2] == "--use-preset-rates";

    /* This will use the hard coded conversion rates provided in the  github task page */
    CurrencyConverter::setUsePresetRates($usePresetRates);

    $csvColumns = [
        'operation_date',
        'user_id',
        'client_type',
        'operation_type',
        'operation_amount',
        'operation_currency'
    ];
    $csvParser = new CsvParser();
    $csvData = $csvParser->parseFile($csvFilePath, $csvColumns,
    [
        'operation_type' => function(string $operationType): string {
            return strtoupper($operationType);
        },
        'operation_date' => function(string $dateString): DateTime {
            return DateTime::createFromFormat(Operation::DATE_FORMAT, $dateString);
        },
        'client_type' => function(string $clientType): string {
            return strtoupper($clientType);
        }
    ]);

    $ledger = new Ledger();

    foreach ($csvData as $row) {
        $client = $ledger->getOrCreateClient($row['user_id'], $row['client_type']);
        
        $operation = Operation::createOperation($client, $row['operation_type'], $row['operation_date'], $row['operation_amount'], $row['operation_currency']);

        $ledger->storeOperation($operation);

        echo $operation->getCommissionFee() . PHP_EOL;
    }
}

main($argc, $argv);