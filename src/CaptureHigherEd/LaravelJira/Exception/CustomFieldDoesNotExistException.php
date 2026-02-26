<?php

namespace CaptureHigherEd\LaravelJira\Exception;

final class CustomFieldDoesNotExistException extends \RuntimeException
{
    public function __construct(string $fieldName = '')
    {
        $message = $fieldName
            ? "Custom field \"{$fieldName}\" does not exist."
            : 'Custom field does not exist.';

        parent::__construct($message);
    }
}
