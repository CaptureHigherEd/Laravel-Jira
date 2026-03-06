<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Hydrator;

use Psr\Http\Message\ResponseInterface;

interface Hydrator
{
    /**
     * Hydrate a successful (2xx) HTTP response into a usable value.
     *
     * Error checking (4xx/5xx) is performed by the caller before invoking the hydrator.
     *
     * @param  class-string|null  $class  Target model class to instantiate, or null for raw data
     */
    public function hydrate(ResponseInterface $response, ?string $class): mixed;
}
