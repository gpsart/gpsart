<?php

declare(strict_types=1);

namespace App\Api\Exception;

use Exception;
use Throwable;

class FailedToCreatePreviewException extends Exception
{
    public static function becauseThereWasExceptionDuringRequest(Throwable $throwable): self
    {
        return new self($throwable);
    }

    public static function becauseResponseCodeIsNotOk(int $responseCode): self
    {
        return new self('GPX to image service response has code ' . $responseCode);
    }
}