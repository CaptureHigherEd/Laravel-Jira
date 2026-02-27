<?php

namespace CaptureHigherEd\LaravelJira\Http;

use CaptureHigherEd\LaravelJira\Hydrator\Hydrator;
use CaptureHigherEd\LaravelJira\Hydrator\ModelHydrator;
use Psr\Http\Client\ClientInterface;

final class HttpClientConfig
{
    /**
     * @param  array<string, string>  $defaultHeaders
     */
    public function __construct(
        public readonly ClientInterface $httpClient,
        public readonly RequestBuilder $requestBuilder,
        public readonly string $baseUri,
        public readonly array $defaultHeaders = [],
        public readonly Hydrator $hydrator = new ModelHydrator,
    ) {}
}
