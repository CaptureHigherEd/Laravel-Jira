<?php

namespace CaptureHigherEd\LaravelJira\Exception;

use Psr\Http\Message\ResponseInterface;

final class HttpClientException extends \RuntimeException
{
    private ResponseInterface|null $response;

    private array $responseBody = [];

    private int $responseCode;

    public function __construct(string $message, int $code, ResponseInterface $response)
    {
        parent::__construct($message, $code);

        $this->response = $response;
        $this->responseCode = $response->getStatusCode();

        $body = $response->getBody()->__toString();

        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $this->responseBody['message'] = $body;
        } elseif ($body) {
            $this->responseBody = json_decode($body, true);
        }
    }

    public static function badRequest(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();

        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;
        }

        $message = sprintf("The parameters passed to the API were invalid. Check your inputs!\n\n%s", $validationMessage);

        return new self($message, 400, $response);
    }

    public static function unauthorized(ResponseInterface $response)
    {
        return new self('Your credentials are incorrect.', 401, $response);
    }

    public static function requestFailed(ResponseInterface $response)
    {
        return new self('Parameters were valid but request failed. Try again.', 402, $response);
    }

    public static function notFound(ResponseInterface $response)
    {
        return new self('The endpoint you have tried to access does not exist.', 404, $response);
    }

    public static function conflict(ResponseInterface $response)
    {

        return new self('Request conflicts with current state of the target resource.', 409, $response);
    }

    public static function payloadTooLarge(ResponseInterface $response)
    {
        return new self('Payload too large, your total attachment size is too big.', 413, $response);
    }

    public static function tooManyRequests(ResponseInterface $response)
    {
        return new self('Too many requests.', 429, $response);
    }

    public static function forbidden(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();

        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['Error']) ? $jsonDecoded['Error'] : $body;
        }

        $message = sprintf("Forbidden!\n\n%s", $validationMessage);

        return new self($message, 403, $response);
    }

    public static function unknown(ResponseInterface $response)
    {
        $message = "Unknown Error";

        return new self($message, $response->getStatusCode(), $response);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}
