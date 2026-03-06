<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Exception;

use CaptureHigherEd\LaravelJira\Exception\Concerns\ParsesResponseBody;
use Psr\Http\Message\ResponseInterface;

final class HttpClientException extends \RuntimeException implements JiraException
{
    use ParsesResponseBody;

    public function __construct(string $message, int $code, ResponseInterface $response)
    {
        parent::__construct($message, $code);

        $this->parseResponse($response);
    }

    public static function badRequest(ResponseInterface $response): self
    {
        $body = $response->getBody()->__toString();

        if (! str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;
        }

        $message = sprintf("The parameters passed to the API were invalid. Check your inputs!\n\n%s", $validationMessage);

        return new self($message, 400, $response);
    }

    public static function unauthorized(ResponseInterface $response): self
    {
        return new self('Your credentials are incorrect.', 401, $response);
    }

    public static function requestFailed(ResponseInterface $response): self
    {
        return new self('Parameters were valid but request failed. Try again.', 402, $response);
    }

    public static function notFound(ResponseInterface $response): self
    {
        return new self('The endpoint you have tried to access does not exist.', 404, $response);
    }

    public static function conflict(ResponseInterface $response): self
    {
        return new self('Request conflicts with current state of the target resource.', 409, $response);
    }

    public static function payloadTooLarge(ResponseInterface $response): self
    {
        return new self('Payload too large, your total attachment size is too big.', 413, $response);
    }

    public static function tooManyRequests(ResponseInterface $response): self
    {
        $retryAfter = $response->getHeaderLine('Retry-After');
        $message = $retryAfter
            ? "Too many requests. Retry after {$retryAfter} seconds."
            : 'Too many requests.';

        return new self($message, 429, $response);
    }

    public static function unprocessableEntity(ResponseInterface $response): self
    {
        $body = $response->getBody()->__toString();

        if (! str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['errorMessages']) ? implode(', ', $jsonDecoded['errorMessages']) : $body;
        }

        $message = sprintf("Unprocessable entity. Jira validation failed.\n\n%s", $validationMessage);

        return new self($message, 422, $response);
    }

    public static function forbidden(ResponseInterface $response): self
    {
        $body = $response->getBody()->__toString();

        if (! str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['Error']) ? $jsonDecoded['Error'] : $body;
        }

        $message = sprintf("Forbidden!\n\n%s", $validationMessage);

        return new self($message, 403, $response);
    }

    public static function unknown(ResponseInterface $response): self
    {
        return new self('Unknown Error', $response->getStatusCode(), $response);
    }
}
