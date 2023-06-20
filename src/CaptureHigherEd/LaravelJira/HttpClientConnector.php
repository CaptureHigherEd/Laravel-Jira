<?php

namespace CaptureHigherEd\LaravelJira;

use GuzzleHttp\Client;

class HttpClientConnector
{
    protected string|null $endpoint;
    protected string|null $apiKey;

    public function __construct()
    {
    }

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

    public function createClient(): Client
    {
        return new Client([
            'http_errors' => false,
            'base_uri' => $this->endpoint,
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Basic {$this->apiKey}",
            ]
        ]);
    }
}
