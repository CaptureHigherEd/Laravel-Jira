<?php

namespace CaptureHigherEd\LaravelJira\Exception;

use Psr\Http\Message\ResponseInterface;

final class HttpServerException extends \RuntimeException implements JiraException
{
    private ?ResponseInterface $response;

    /** @var array<string, mixed> */
    private array $responseBody = [];

    private int $responseCode;

    public function __construct(string $message, int $code, ResponseInterface $response)
    {
        parent::__construct($message, $code);

        $this->response = $response;
        $this->responseCode = $response->getStatusCode();

        $response->getBody()->rewind();
        $body = $response->getBody()->__toString();

        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
            $this->responseBody['message'] = $body;
        } elseif ($body) {
            $this->responseBody = json_decode($body, true) ?? [];
        }
    }

    public static function serverError(ResponseInterface $response): self
    {
        $statusCode = $response->getStatusCode();

        return new self(
            sprintf('Jira server error (HTTP %d). Please try again later.', $statusCode),
            $statusCode,
            $response
        );
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
