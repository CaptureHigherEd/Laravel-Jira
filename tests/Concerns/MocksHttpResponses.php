<?php

namespace CaptureHigherEd\LaravelJira\Tests\Concerns;

use CaptureHigherEd\LaravelJira\Http\HttpClientConfig;
use CaptureHigherEd\LaravelJira\Http\RequestBuilder;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\ResponseInterface;

trait MocksHttpResponses
{
    /**
     * @param  array<string, mixed>|string|null  $body
     * @param  array<string, string>  $headers
     */
    protected function mockResponse(int $status, array|string|null $body = null, array $headers = []): Response
    {
        $bodyString = is_array($body) ? json_encode($body) : ($body ?? '');

        if (is_array($body) && ! isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }

        return new Response($status, $headers, $bodyString);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function jsonResponse(array $body, int $status = 200): Response
    {
        return $this->mockResponse($status, $body, ['Content-Type' => 'application/json']);
    }

    protected function noContentResponse(): Response
    {
        return new Response(204, [], '');
    }

    protected function plainErrorResponse(int $status, string $body): Response
    {
        return new Response($status, ['Content-Type' => 'text/plain'], $body);
    }

    protected function makeRequestBuilder(): RequestBuilder
    {
        $factory = new HttpFactory;

        return new RequestBuilder($factory, $factory);
    }

    /**
     * Build an HttpClientConfig backed by a PSR-18 mock that always returns $response.
     *
     * @param  array<string, string>  $defaultHeaders
     */
    protected function makeConfig(ResponseInterface $response, array $defaultHeaders = []): HttpClientConfig
    {
        $client = $this->createMock(PsrClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        return new HttpClientConfig(
            $client,
            $this->makeRequestBuilder(),
            'https://test.atlassian.net/rest/api/3/',
            $defaultHeaders
        );
    }

    /**
     * Build an HttpClientConfig backed by a PSR-18 mock that returns responses in sequence.
     *
     * @param  array<int, ResponseInterface>  $responses
     */
    protected function makeConfigWithResponses(array $responses): HttpClientConfig
    {
        $client = $this->createMock(PsrClientInterface::class);
        $client->method('sendRequest')->willReturnOnConsecutiveCalls(...$responses);

        return new HttpClientConfig(
            $client,
            $this->makeRequestBuilder(),
            'https://test.atlassian.net/rest/api/3/',
        );
    }
}
