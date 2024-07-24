<?php

declare(strict_types=1);

namespace BankApp\Service\File;

use BankApp\Service\Exception\FileNotFoundException;
use BankApp\Service\Exception\InvalidCsvStructureException;
use BankApp\Service\Exception\InvalidFileFormatException;

class CsvParser
{
    /**
     * @var string
     */
    private $separator;

    /**
     * @var int
     */
    private $lineLength;

    public function __construct(string $separator = ',', int $lineLength = 0)
    {
        $this->separator = $separator;
        $this->lineLength = $lineLength;
    }

    public function parseFile(string $path, array $columnNames, array $columnTransforms = [])
    {
        if (!$this->validateFileFormat($path, FileFormat::CSV)) {
            throw new InvalidFileFormatException($path, FileFormat::CSV);
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        $file = fopen($path, 'r');

        if ($file === false) {
            throw new FileNotFoundException($path);
        }

        $expectedColumnsPerRow = count($columnNames);

        $row = 1;
        $results = [];
        while (($data = fgetcsv($file, 0, $this->separator)) !== false) {
            $columnCount = count($data);

            if ($expectedColumnsPerRow !== null && $columnCount !== $expectedColumnsPerRow) {
                throw new InvalidCsvStructureException($path, $row, $this->lineLength, sprintf('Expected %s columns in row', $expectedColumnsPerRow));
            }

            $rowObject = [];
            for ($col = 0; $col < $columnCount; ++$col) {
                $colName = $columnNames[$col];
                $colValue = $data[$col];

                /* Use passed transform function for column if it exists to transform the value */
                if (array_key_exists($colName, $columnTransforms)) {
                    $colValue = $columnTransforms[$colName]($colValue);
                }

                $rowObject[$columnNames[$col]] = $colValue;
            }

            array_push($results, $rowObject);

            ++$row;
        }

        fclose($file);

        return $results;
    }

    public static function validateFileFormat(string $path, string $expectedFormat)
    {
        $info = pathinfo($path);

        return array_key_exists('extension', $info) && $info['extension'] === $expectedFormat;
    }
}
