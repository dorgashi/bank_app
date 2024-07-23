<?php

declare(strict_types=1);

namespace BankApp\Exception;

interface ExceptionCode {
    const FILE_NOT_FOUND = 0;
    const INVALID_FILE_FORMAT = 1;
    const INVALID_CSV_STRUCTURE = 2;
};