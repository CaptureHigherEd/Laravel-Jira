<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Exception\Concerns;

use Psr\Http\Message\ResponseInterface;

trait ParsesResponseBody
{
    private ?ResponseInterface $response;

    /** @var array<string, mixed> */
    private array $responseBody = [];

    private int $responseCode;

    private function parseResponse(?ResponseInterface $response): void
    {
        $this->response = $response;

        if ($response === null) {
            $this->responseCode = 0;

            return;
        }

        $this->responseCode = $response->getStatusCode();

        $response->getBody()->rewind();
        $body = $response->getBody()->__toString();

        if (! str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            $this->responseBody['message'] = $body;
        } elseif ($body) {
            $this->responseBody = json_decode($body, true) ?? [];
        }
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}
