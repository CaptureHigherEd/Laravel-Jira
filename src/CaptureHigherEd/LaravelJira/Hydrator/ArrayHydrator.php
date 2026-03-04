<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Hydrator;

use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Always decodes the response body as a plain array, ignoring any target class.
 * Useful for debugging or when raw data is preferred over model instances.
 *
 * @return array<mixed>
 */
final class ArrayHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, ?string $class): mixed
    {
        if ($response->getStatusCode() === 204) {
            return [];
        }

        $body = (string) $response->getBody();

        if ($body === '') {
            return [];
        }

        $data = json_decode($body, true);

        if (! is_array($data)) {
            throw HydrationException::jsonDecodeFailed($body);
        }

        return $data;
    }
}
