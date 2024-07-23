<?php

require_once './vendor/autoload.php';

use BankApp\Service\CsvParser;

function main(int $argc, array $argv) {
    if ($argc < 2) throw new Error("Usage is \"php script.php path/to/file.csv\"");

    $csvColumns = [
        'operation_date',
        'user_id',
        'user_type',
        'operation_type',
        'operation_amount',
        'operation_currency'
    ];
    $csvParser = new CsvParser();
    $csvData = $csvParser->parseFile($argv[1], $csvColumns);
}

main($argc, $argv);