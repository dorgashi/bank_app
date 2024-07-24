<?php

declare(strict_types=1);

namespace BankApp\Service\File;

/* Would use enums if PHP >= 8.1 */
abstract class FileFormat
{
    /**
     * @var string
     */
    public const CSV = 'csv';
}
