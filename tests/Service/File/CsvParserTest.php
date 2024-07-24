<?php

declare(strict_types=1);

namespace BankApp\Tests\Service\File;

use PHPUnit\Framework\TestCase;
use BankApp\Service\File\CsvParser;
use BankApp\Service\Operation\Operation;

use BankApp\Service\Exception\ExceptionCode;
use BankApp\Service\Exception\InvalidFileFormatException;
use BankApp\Service\Exception\FileNotFoundException;

class CsvParserTest extends TestCase
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $filePathInvalidFormat;
    
    /**
     * @var array
     */
    private $csvColumns;

    /**
     * @var array
     */
    private $csvColumnTransforms;

    /**
     * @var array
     */
    private $expectedCsvData;

    public function setUp()
    {
        $testsDir = dirname(__DIR__, 2);
        $this->filePath = "$testsDir/Data/CsvParserTestData.csv";
        $this->filePathInvalidFormat = "$testsDir/testcsv_invalid_format.txt";

        $this->csvColumns = [
            'operation_date',
            'user_id',
            'client_type',
            'operation_type',
            'operation_amount',
            'operation_currency'
        ];

        $this->csvColumnTransforms = [
            'operation_date' => function(string $dateString): \DateTime {
                return \DateTime::createFromFormat(Operation::DATE_FORMAT, $dateString);
            }
        ];

        $this->expectedCsvData = $this->loadJsonData("$testsDir/Data/CsvParserTestData.json");
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
     * Test parse of testcsv.csv
     */
    public function testParseFile(): void
    {
        $csvParser = new CsvParser();
        $data = $csvParser->parseFile($this->filePath, $this->csvColumns, $this->csvColumnTransforms);

        /* Test file has 12 rows */
        $this->assertEquals($data, $this->expectedCsvData);
    }

    /**
     * Test parse with invalid file format
     */
    public function testInvalidFileFormatException(): void
    {
        $this->expectException(InvalidFileFormatException::class);
        $this->expectExceptionCode(ExceptionCode::INVALID_FILE_FORMAT);
        $this->expectExceptionMessage("Invalid file format provided \"$this->filePathInvalidFormat\". Expected format: csv");

        $csvParser = new CsvParser();
        $csvParser->parseFile($this->filePathInvalidFormat, $this->csvColumns, $this->csvColumnTransforms);
    }

    /**
     * Test parse with missing file
     */
    public function testFileNotFoundException(): void
    {
        $wrongPath = __DIR__ . "./CSV_FILE.csv";
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionCode(ExceptionCode::FILE_NOT_FOUND);
        $this->expectExceptionMessage("File not found: \"$wrongPath\"");

        $csvParser = new CsvParser();
        $csvParser->parseFile($wrongPath, $this->csvColumns, $this->csvColumnTransforms);
    }

    /**
     * @param string $path
     * @param string $expectedFormat
     * @param bool $expectation
     *
     * @dataProvider dataProviderForValidateFileFormatTesting
     */
    public function testValidateFileFormat(string $path, string $expectedFormat, bool $expectation): void
    {
        $this->assertEquals(
            $expectation,
            CsvParser::validateFileFormat($path, $expectedFormat)
        );
    }

    public function dataProviderForValidateFileFormatTesting(): array {
        return [
            ['some/path.txt', 'txt', true],
            ['other/path.csv', 'pdf', false],
            ['some/other/path', 'jpg', false]
        ];
    }
}
