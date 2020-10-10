<?php

declare(strict_types=1);

namespace App\Api\Exception;

use Exception;

class BadGpxFileException extends Exception
{
    public static function becauseThereIsNoActivityNode(): self
    {
        return new self("Unable to find valid activity in file contents");
    }
}