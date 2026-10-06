<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Exception;

use CaptureHigherEd\LaravelJira\Exception\Concerns\ParsesResponseBody;
use Psr\Http\Message\ResponseInterface;

final class HttpServerException extends \RuntimeException implements JiraException
{
    use ParsesResponseBody;

    public function __construct(string $message, int $code, ?ResponseInterface $response, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->parseResponse($response);
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

    public static function networkError(\Throwable $previous): self
    {
        return new self('A network error occurred. Check connectivity and try again.', 0, null, $previous);
    }

    public static function unknownHttpResponseCode(ResponseInterface $response): self
    {
        $statusCode = $response->getStatusCode();

        return new self(
            sprintf('Unexpected HTTP response code %d.', $statusCode),
            $statusCode,
            $response
        );
    }
}
