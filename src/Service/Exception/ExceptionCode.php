<?php

declare(strict_types=1);

namespace BankApp\Service\Exception;

/* Would use enums if PHP >= 8.1 */
abstract class ExceptionCode
{
    /**
     * @var int
     */
    public const FILE_NOT_FOUND = 0;

    /**
     * @var int
     */
    public const INVALID_FILE_FORMAT = 1;

    /**
     * @var int
     */
    public const INVALID_CSV_STRUCTURE = 2;

    /**
     * @var int
     */
    public const OPERATION_ALREADY_EXECUTED = 3;

    /**
     * @var int
     */
    public const OPERATION_INVALID_TYPE = 4;

    /**
     * @var int
     */
    public const INVALID_CURRENCY = 5;

    /**
     * @var int
     */
    public const DATE_MALFORMED_STRING = 6;

    /**
     * @var int
     */
    public const INVALID_CLIENT_TYPE = 7;
}
