<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira;

use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;

final class Assert
{
    public static function stringNotEmpty(string $value, string $message): void
    {
        if ($value === '') {
            throw new InvalidArgumentException($message);
        }
    }
}
