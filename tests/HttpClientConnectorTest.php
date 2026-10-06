<?php

namespace CaptureHigherEd\LaravelJira\Tests;

use CaptureHigherEd\LaravelJira\Http\HttpClientConfig;
use CaptureHigherEd\LaravelJira\HttpClientConnector;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class HttpClientConnectorTest extends TestCase
{
    private HttpClientConnector $connector;

    protected function setUp(): void
    {
        $this->connector = (new HttpClientConnector)
            ->setEndpoint('https://test.atlassian.net/rest/api/3/')
            ->setApiKey('dGVzdEBleGFtcGxlLmNvbTpmYWtlLXRva2Vu');
    }

    public function test_create_config_returns_http_client_config(): void
    {
        $config = $this->connector->createConfig();

        $this->assertInstanceOf(HttpClientConfig::class, $config, 'createConfig() should return an HttpClientConfig instance');
    }

    public function test_create_config_sets_base_uri(): void
    {
        $config = $this->connector->createConfig();

        $this->assertSame(
            'https://test.atlassian.net/rest/api/3/',
            $config->baseUri,
            'HttpClientConfig baseUri should match the configured endpoint'
        );
    }

    public function test_create_config_sets_authorization_header(): void
    {
        $config = $this->connector->createConfig();

        $this->assertSame(
            'Basic dGVzdEBleGFtcGxlLmNvbTpmYWtlLXRva2Vu',
            $config->defaultHeaders['Authorization'],
            'Authorization header should be a Basic token using the configured API key'
        );
    }

    public function test_create_config_sets_accept_header(): void
    {
        $config = $this->connector->createConfig();

        $this->assertSame('application/json', $config->defaultHeaders['Accept'], 'Accept header should be application/json');
    }

    public function test_create_config_http_client_is_psr18(): void
    {
        $config = $this->connector->createConfig();

        $this->assertInstanceOf(ClientInterface::class, $config->httpClient, 'httpClient should implement PSR-18 ClientInterface');
    }
}
