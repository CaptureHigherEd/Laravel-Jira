<?php

namespace CaptureHigherEd\LaravelJira\Exception;

final class HydrationException extends \RuntimeException implements JiraException
{
    public static function jsonDecodeFailed(string $body): self
    {
        $preview = strlen($body) > 100 ? substr($body, 0, 100).'...' : $body;

        return new self(sprintf('Failed to JSON decode response body: %s', $preview));
    }

    public static function unexpectedContentType(string $contentType): self
    {
        return new self(sprintf('Unexpected content-type "%s", expected application/json.', $contentType));
    }
}
