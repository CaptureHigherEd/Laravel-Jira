<?php

namespace CaptureHigherEd\LaravelJira\Hydrator;

use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Decodes JSON and hydrates the result into a model via its static make() factory,
 * or returns the raw decoded array when no class is provided.
 */
final class ModelHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, ?string $class): mixed
    {
        if ($response->getStatusCode() === 204) {
            return $class ? $class::make([]) : [];
        }

        $body = (string) $response->getBody();

        if ($body === '') {
            return $class ? $class::make([]) : [];
        }

        $data = json_decode($body, true);

        if (! is_array($data)) {
            throw HydrationException::jsonDecodeFailed($body);
        }

        if (! $class) {
            return $data;
        }

        return call_user_func([$class, 'make'], $data);
    }
}
