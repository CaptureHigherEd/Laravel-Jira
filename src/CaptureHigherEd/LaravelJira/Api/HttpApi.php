<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use CaptureHigherEd\LaravelJira\Exception\HttpServerException;
use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use CaptureHigherEd\LaravelJira\Models\ApiResponse;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    protected ClientInterface $httpClient;

    protected ?ResponseInterface $lastResponse = null;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  Query string parameters
     */
    protected function httpGet(string $path, array $parameters = []): ResponseInterface
    {
        return $this->lastResponse = $this->httpClient->request('GET', $path, ['query' => $parameters]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  JSON request body
     */
    protected function httpPost(string $path, array $parameters = []): ResponseInterface
    {
        return $this->lastResponse = $this->httpClient->request('POST', $path, ['json' => $parameters]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<int, array<string, mixed>>  $multipart  Guzzle multipart form data
     */
    protected function httpPostWithAttachments(string $path, array $multipart = []): ResponseInterface
    {
        return $this->lastResponse = $this->httpClient->request('POST', $path, ['multipart' => $multipart, 'headers' => [
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
        return $this->lastResponse = $this->httpClient->request('PUT', $path, ['json' => $parameters]);
    }

    /**
     * @param  string  $path  Relative API path
     * @param  array<string, mixed>  $parameters  Query string parameters
     */
    protected function httpDelete(string $path, array $parameters = []): ResponseInterface
    {
        return $this->lastResponse = $this->httpClient->request('DELETE', $path, ['query' => $parameters]);
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
                throw HttpServerException::serverError($response);
            default:
                if ($statusCode >= 500) {
                    throw HttpServerException::serverError($response);
                }
                throw HttpClientException::unknown($response);
        }
    }

    /**
     * Paginate through all pages of a GET endpoint.
     *
     * @template T of Paginated&ApiResponse
     *
     * @param  array<string, mixed>  $parameters
     * @param  class-string<T>  $class
     * @return \Generator<int, T, mixed, void>
     */
    protected function paginateGet(string $path, array $parameters, string $class): \Generator
    {
        $page = 0;
        do {
            $response = $this->httpGet($path, $parameters);
            $model = $this->hydrateResponse($response, $class);
            yield $page => $model;
            $page++;
            if (! $model->hasMore()) {
                break;
            }
            $parameters['startAt'] = $model->getNextStartAt();
        } while (true);
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

        $data = json_decode($body, true);

        if (! is_array($data)) {
            throw HydrationException::jsonDecodeFailed($body);
        }

        if (! $class) {
            return $data;
        }

        $object = call_user_func([$class, 'make'], $data);

        return $object;
    }
}
