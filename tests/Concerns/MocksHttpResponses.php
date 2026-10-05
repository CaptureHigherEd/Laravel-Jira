<?php

namespace CaptureHigherEd\LaravelJira\Tests\Concerns;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
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

    protected function mockClient(ResponseInterface $response): ClientInterface
    {
        $client = $this->createMock(ClientInterface::class);
        $client->method('request')->willReturn($response);

        return $client;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function mockClientExpecting(string $method, string $path, array $options, ResponseInterface $response): ClientInterface
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('request')
            ->with($method, $path, $options)
            ->willReturn($response);

        return $client;
    }

    /**
     * @param  array<int, ResponseInterface>  $responses
     */
    protected function mockClientWithResponses(array $responses): ClientInterface
    {
        $client = $this->createMock(ClientInterface::class);
        $client->method('request')->willReturnOnConsecutiveCalls(...$responses);

        return $client;
    }
}
