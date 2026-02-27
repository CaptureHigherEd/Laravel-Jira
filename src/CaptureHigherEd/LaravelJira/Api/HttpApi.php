<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Exception\HttpClientException;
use CaptureHigherEd\LaravelJira\Exception\HttpServerException;
use CaptureHigherEd\LaravelJira\Http\HttpClientConfig;
use CaptureHigherEd\LaravelJira\Models\ApiResponse;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    protected ?ResponseInterface $lastResponse = null;

    public function __construct(protected HttpClientConfig $config) {}

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }

    /**
     * @param  array<string, mixed>  $parameters  Query string parameters
     */
    protected function httpGet(string $path, array $parameters = []): ResponseInterface
    {
        $request = $this->applyDefaultHeaders(
            $this->config->requestBuilder->create('GET', $this->buildUri($path, $parameters))
        );

        return $this->lastResponse = $this->config->httpClient->sendRequest($request);
    }

    /**
     * @param  array<string, mixed>  $parameters  JSON request body
     */
    protected function httpPost(string $path, array $parameters = []): ResponseInterface
    {
        $request = $this->applyDefaultHeaders(
            $this->config->requestBuilder->createWithJson('POST', $this->buildUri($path), $parameters)
        );

        return $this->lastResponse = $this->config->httpClient->sendRequest($request);
    }

    /**
     * @param  array<int, array<string, mixed>>  $multipart  Multipart form data parts
     */
    protected function httpPostWithAttachments(string $path, array $multipart = []): ResponseInterface
    {
        $request = $this->applyDefaultHeaders(
            $this->config->requestBuilder->createWithMultipart('POST', $this->buildUri($path), $multipart)
        )->withHeader('X-Atlassian-Token', 'no-check');

        return $this->lastResponse = $this->config->httpClient->sendRequest($request);
    }

    /**
     * POST with a raw string body (e.g. Jira's addWatcher quirk requires a JSON-encoded string).
     */
    protected function httpPostRaw(string $path, string $body, string $contentType = 'application/json'): ResponseInterface
    {
        $request = $this->applyDefaultHeaders(
            $this->config->requestBuilder->createWithRawBody('POST', $this->buildUri($path), $body, $contentType)
        );

        return $this->lastResponse = $this->config->httpClient->sendRequest($request);
    }

    /**
     * @param  array<string, mixed>  $parameters  JSON request body
     */
    protected function httpPut(string $path, array $parameters = []): ResponseInterface
    {
        $request = $this->applyDefaultHeaders(
            $this->config->requestBuilder->createWithJson('PUT', $this->buildUri($path), $parameters)
        );

        return $this->lastResponse = $this->config->httpClient->sendRequest($request);
    }

    /**
     * @param  array<string, mixed>  $parameters  Query string parameters
     */
    protected function httpDelete(string $path, array $parameters = []): ResponseInterface
    {
        $request = $this->applyDefaultHeaders(
            $this->config->requestBuilder->create('DELETE', $this->buildUri($path, $parameters))
        );

        return $this->lastResponse = $this->config->httpClient->sendRequest($request);
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
        if (! in_array($response->getStatusCode(), [200, 201, 202, 204], true)) {
            $this->handleErrors($response);
        }

        return $this->config->hydrator->hydrate($response, $class);
    }

    /**
     * Build a full URI from a relative path and optional query parameters.
     *
     * @param  array<string, mixed>  $parameters
     */
    private function buildUri(string $path, array $parameters = []): string
    {
        $uri = rtrim($this->config->baseUri, '/').'/'.$path;

        if (! empty($parameters)) {
            $uri .= '?'.http_build_query($parameters);
        }

        return $uri;
    }

    /**
     * Apply default headers to a request without overriding headers already set on the request.
     */
    private function applyDefaultHeaders(RequestInterface $request): RequestInterface
    {
        foreach ($this->config->defaultHeaders as $name => $value) {
            if (! $request->hasHeader($name)) {
                $request = $request->withHeader($name, $value);
            }
        }

        return $request;
    }
}
