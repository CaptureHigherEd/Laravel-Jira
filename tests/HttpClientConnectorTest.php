<?php

namespace CaptureHigherEd\LaravelJira\Tests;

use CaptureHigherEd\LaravelJira\HttpClientConnector;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class HttpClientConnectorTest extends TestCase
{
    private HttpClientConnector $connector;

    protected function setUp(): void
    {
        $this->connector = (new HttpClientConnector)
            ->setEndpoint('https://test.atlassian.net/rest/api/3/')
            ->setApiKey('dGVzdEBleGFtcGxlLmNvbTpmYWtlLXRva2Vu');
    }

    public function test_create_client_returns_guzzle_client(): void
    {
        $client = $this->connector->createClient();

        $this->assertInstanceOf(Client::class, $client, 'createClient() should return a GuzzleHttp\\Client instance');
    }

    public function test_create_client_sets_base_uri(): void
    {
        $client = $this->connector->createClient();

        $this->assertSame(
            'https://test.atlassian.net/rest/api/3/',
            (string) $client->getConfig('base_uri'),
            'Guzzle client base_uri should match the configured endpoint'
        );
    }

    public function test_create_client_sets_authorization_header(): void
    {
        $client = $this->connector->createClient();
        $headers = $client->getConfig('headers');

        $this->assertSame('Basic dGVzdEBleGFtcGxlLmNvbTpmYWtlLXRva2Vu', $headers['Authorization'], 'Authorization header should be a Basic token using the configured API key');
    }

    public function test_create_client_disables_http_errors(): void
    {
        $client = $this->connector->createClient();

        $this->assertFalse($client->getConfig('http_errors'), 'Guzzle http_errors option should be disabled so errors are handled manually');
    }
}
