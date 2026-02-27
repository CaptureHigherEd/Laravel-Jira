<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    protected ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  Query string parameters
     */
    protected function httpGet(string $path, array $parameters = []): ResponseInterface
    {
        return $this->httpClient->request('GET', $path, ['query' => $parameters]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  JSON request body
     */
    protected function httpPost(string $path, array $parameters = []): ResponseInterface
    {
        return $this->httpClient->request('POST', $path, ['json' => $parameters]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<int, array<string, mixed>>  $multipart  Guzzle multipart form data
     */
    protected function httpPostWithAttachments(string $path, array $multipart = []): ResponseInterface
    {
        return $this->httpClient->request('POST', $path, ['multipart' => $multipart, 'headers' => [
            'Accept' => 'application/json',
            'X-Atlassian-Token' => 'no-check',
        ]]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  JSON request body
     */
    protected function httpPut(string $path, array $parameters = []): ResponseInterface
    {
        return $this->httpClient->request('PUT', $path, ['json' => $parameters]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  Query string parameters
     */
    protected function httpDelete(string $path, array $parameters = []): ResponseInterface
    {
        return $this->httpClient->request('DELETE', $path, ['query' => $parameters]);
    }

    protected function handleErrors(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 400:
                throw HttpClientException::badRequest($response);
            case 401:
                throw HttpClientException::unauthorized($response);
            case 402:
                throw HttpClientException::requestFailed($response);
            case 403:
                throw HttpClientException::forbidden($response);
            case 404:
                throw HttpClientException::notFound($response);
            case 409:
                throw HttpClientException::conflict($response);
            case 413:
                throw HttpClientException::payloadTooLarge($response);
            case 422:
                throw HttpClientException::unprocessableEntity($response);
            case 429:
                throw HttpClientException::tooManyRequests($response);
            case 500:
            case 502:
            case 503:
                throw HttpClientException::serverError($response);
            default:
                throw HttpClientException::unknown($response);
        }
    }

    protected function hydrateResponse(ResponseInterface $response, ?string $class = null): mixed
    {
        $statusCode = $response->getStatusCode();

        if (! in_array($statusCode, [200, 201, 202, 204], true)) {
            $this->handleErrors($response);
        }

        if ($statusCode === 204) {
            return $class ? $class::make([]) : [];
        }

        $body = (string) $response->getBody();

        if ($body === '') {
            return $class ? $class::make([]) : [];
        }

        $data = json_decode($body, true) ?? [];

        if (! $class) {
            return $data;
        }

        $object = call_user_func([$class, 'make'], $data);

        return $object;
    }
}
