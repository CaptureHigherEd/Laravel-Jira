<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\HttpClient;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    use MocksHttpResponses;

    public function test_http_get_is_public_and_returns_response(): void
    {
        $response = $this->jsonResponse(['key' => 'FOO-1']);
        $client = new HttpClient($this->makeConfig($response));

        $result = $client->httpGet('issue/FOO-1');

        $this->assertSame($response, $result, 'HttpClient::httpGet() should return the PSR-7 response');
    }

    public function test_http_post_is_public_and_returns_response(): void
    {
        $response = $this->jsonResponse(['id' => '1', 'key' => 'FOO-1', 'fields' => []]);
        $client = new HttpClient($this->makeConfig($response));

        $result = $client->httpPost('issue', ['fields' => ['summary' => 'Test']]);

        $this->assertSame($response, $result, 'HttpClient::httpPost() should return the PSR-7 response');
    }

    public function test_http_put_is_public_and_returns_response(): void
    {
        $response = $this->noContentResponse();
        $client = new HttpClient($this->makeConfig($response));

        $result = $client->httpPut('issue/FOO-1', ['fields' => ['summary' => 'Updated']]);

        $this->assertSame($response, $result, 'HttpClient::httpPut() should return the PSR-7 response');
    }

    public function test_http_delete_is_public_and_returns_response(): void
    {
        $response = $this->noContentResponse();
        $client = new HttpClient($this->makeConfig($response));

        $result = $client->httpDelete('issue/FOO-1');

        $this->assertSame($response, $result, 'HttpClient::httpDelete() should return the PSR-7 response');
    }

    public function test_http_post_raw_is_public_and_returns_response(): void
    {
        $response = $this->noContentResponse();
        $client = new HttpClient($this->makeConfig($response));

        $result = $client->httpPostRaw('issue/FOO-1/watchers', '"user-account-id"');

        $this->assertSame($response, $result, 'HttpClient::httpPostRaw() should return the PSR-7 response');
    }

    public function test_get_last_response_reflects_most_recent_call(): void
    {
        $response = $this->jsonResponse([]);
        $client = new HttpClient($this->makeConfig($response));

        $client->httpGet('issue/FOO-1');

        $this->assertSame($response, $client->getLastResponse(), 'getLastResponse() should return the response from the most recent call');
    }
}
