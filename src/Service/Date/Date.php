<?php

declare(strict_types=1);

namespace BankApp\Service\Date;

use BankApp\Service\Exception\DateMalformedStringException;

class Date
{
    /**
     * @var string
     */
    public const DATE_FORMAT = 'Y-m-d';

    public static function getYear(\DateTime $date): string
    {
        if (!$date) {
            return false;
        }

        return $date->format('Y');
    }

    public static function getWeek(\DateTime $date): string
    {
        if (!$date) {
            return false;
        }

        return $date->format('W');
    }

    public static function getIsoWeek(\DateTime $date): string
    {
        if (!$date) {
            return false;
        }

        return $date->format('oW');
    }

    public static function datesAreInSameWeek(\DateTime $date1, \DateTime $date2): bool
    {
        if (!$date1 || !$date2) {
            return false;
        }

        return $date1->format('oW') === $date2->format('oW');
    }

    public static function createFromFormat(string $dateStr, string $format = Date::DATE_FORMAT): \DateTime
    {
        $d = \DateTime::createFromFormat($format, $dateStr);

        if (!$d || $d->format($format) !== $dateStr) {
            throw new DateMalformedStringException($dateStr, $format);
        }

        return $d;
    }

    public static function isValidDateString(string $dateStr, string $format = Date::DATE_FORMAT): bool
    {
        $d = \DateTime::createFromFormat($format, $dateStr);

        return $d && $d->format($format) === $dateStr;
    }
}
