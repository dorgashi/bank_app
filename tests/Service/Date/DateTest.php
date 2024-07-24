<?php

declare(strict_types=1);

namespace BankApp\Service\Tests\Service\Date;

use PHPUnit\Framework\TestCase;
use BankApp\Service\Date\Date;
use BankApp\Service\Exception\ExceptionCode;
use BankApp\Service\Exception\DateMalformedStringException;

class DateTest extends TestCase
{
    /**
     * @var string
     */
    private $dateFormat;

    public function setUp()
    {
        $this->dateFormat = 'Y-m-d';
    }

    /**
     * @param string $date
     * @param string $expectation
     *
     * @dataProvider dataProviderForGetYearTesting
     */
    public function testGetYear(string $dateStr, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Date::getYear(Date::createFromFormat($dateStr))
        );
    }

    /**
     * @param string $dateStr
     * @param string $expectation
     *
     * @dataProvider dataProviderForGetWeekTesting
     */
    public function testGetWeek(string $dateStr, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Date::getWeek(Date::createFromFormat($dateStr))
        );
    }

    /**
     * @param string $date
     * @param string $expectation
     *
     * @dataProvider dataProviderForGetIsoWeekTesting
     */
    public function testGetIsoWeek(string $dateStr, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Date::getIsoWeek(Date::createFromFormat($dateStr))
        );
    }

    /**
     * @param string $dateStr1
     * @param string $dateStr2
     * @param bool $expectation
     *
     * @dataProvider dataProviderForDatesAreInSameWeekTesting
     */
    public function testDatesAreInSameWeek(string $dateStr1, string $dateStr2, bool $expectation)
    {
        $this->assertEquals(
            $expectation,
            Date::datesAreInSameWeek(Date::createFromFormat($dateStr1), Date::createFromFormat($dateStr2))
        );
    }

    /**
     * @param string $dateStr
     * @param string $expectation
     *
     * @dataProvider dataProviderForCreateFromFormatTesting
     */
    public function testCreateFromFormat(string $dateStr, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            Date::createFromFormat($dateStr, $this->dateFormat)->format($this->dateFormat)
        );
    }

    /**
     * @param string $dateStr
     * @param bool $expectation
     *
     * @dataProvider dataProviderForIsValidDateStringTesting
     */
    public function testIsValidDateString(string $dateStr, bool $expectation)
    {
        $this->assertEquals(
            $expectation,
            Date::isValidDateString($dateStr, $this->dateFormat)
        );
    }

    /**
     * Test exception when given malformed date string
     */
    public function testCreateFromFormatException()
    {
        $this->expectException(DateMalformedStringException::class);
        $this->expectExceptionCode(ExceptionCode::DATE_MALFORMED_STRING);
        $this->expectExceptionMessage("Failed to parse date string \"192-3041-24\", expecting format \"$this->dateFormat\"");

        Date::createFromFormat("192-3041-24");
    }

    public function dataProviderForIsValidDateStringTesting(): array {
        return [
            ['2014-01-02', true],
            ['20111-05-13', false],
            ['02-2014-03', false],
            ['2030-05-12', true]
        ];
    }
    
    public function dataProviderForGetYearTesting(): array {
        return [
            ['2014-01-02', '2014'],
            ['2030-05-12', '2030']
        ];
    }
    
    public function dataProviderForGetWeekTesting(): array {
        return [
            ['2014-01-02', '01'],
            ['2030-05-12', '19']
        ];
    }
    
    public function dataProviderForGetIsoWeekTesting(): array {
        return [
            ['2014-01-02', '201401'],
            ['2030-05-12', '203019']
        ];
    }

    public function dataProviderForDatesAreInSameWeekTesting(): array {
        return [
            ['2014-01-02', '2014-01-02', true],
            ['2027-12-30', '2028-01-01', true]
        ];
    }
    
    public function dataProviderForCreateFromFormatTesting(): array {
        return [
            ['2014-01-02', '2014-01-02'],
            ['2030-01-01', '2030-01-01']
        ];
    }

}
