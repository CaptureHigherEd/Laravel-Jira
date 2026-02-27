<?php

namespace CaptureHigherEd\LaravelJira;

use CaptureHigherEd\LaravelJira\Http\HttpClientConfig;
use CaptureHigherEd\LaravelJira\Http\RequestBuilder;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;

class HttpClientConnector
{
    protected ?string $endpoint;

    protected ?string $apiKey;

    public function __construct() {}

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function createConfig(): HttpClientConfig
    {
        $factory = Psr17FactoryDiscovery::findRequestFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        return new HttpClientConfig(
            httpClient: Psr18ClientDiscovery::find(),
            requestBuilder: new RequestBuilder($factory, $streamFactory),
            baseUri: $this->endpoint ?? '',
            defaultHeaders: [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Basic {$this->apiKey}",
            ],
        );
    }
}
