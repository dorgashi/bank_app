<?php

declare(strict_types=1);

namespace BankApp\Service;

use BankApp\Exception\FileNotFoundException;
use BankApp\Exception\InvalidFileFormatException;
use BankApp\Exception\InvalidCsvStructureException;

interface FileFormat {
    const CSV = "csv";
};

class CsvParser {
    private $separator;
    private $lineLength;

    public function __construct(string $separator = ',', int $lineLength = 0) {
        $this->separator = $separator;
        $this->lineLength = $lineLength;
    }

    public function parseFile(string $path, array $columnNames) {
        if (!$this->validateFileFormat($path, FileFormat::CSV)) {
            throw new InvalidFileFormatException($path, FileFormat::CSV);
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        $file = fopen($path, 'r');

        if ($file == FALSE) {
            throw new FileNotFoundException($path);
        }

        $expectedColumnsPerRow = count($columnNames);

        $row = 1;
        $results = [];
        while (($data = fgetcsv($file, 0, $this->separator)) != FALSE) {
            $columnCount = count($data);

            if ($expectedColumnsPerRow !== null && $columnCount != $expectedColumnsPerRow) {
                throw new InvalidCsvStructureException($path, $row, $this->lineLength, sprintf("Expected %s columns in row", $expectedColumnsPerRow));
            }

            $rowObject = [];
            for ($col = 0; $col < $columnCount; $col++) {
                $rowObject[$columnNames[$col]] = $data[$col];
            }

            array_push($results, $rowObject);

            $row++;
        }

        fclose($file);

        return $results;
    }

    public static function validateFileFormat(string $path, string $expectedFormat) {
        $info = pathinfo($path);
        return $info['extension'] == $expectedFormat;
    }
}