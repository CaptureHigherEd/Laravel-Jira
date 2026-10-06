<?php

namespace CaptureHigherEd\LaravelJira\Hydrator;

use Psr\Http\Message\ResponseInterface;

/**
 * Returns the raw PSR-7 ResponseInterface without any processing.
 * Useful for callers that need access to headers, streaming bodies, or raw status codes.
 */
final class NoopHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, ?string $class): ResponseInterface
    {
        return $response;
    }
}
