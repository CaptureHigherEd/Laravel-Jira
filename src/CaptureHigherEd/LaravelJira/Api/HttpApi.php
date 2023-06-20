<?php

namespace CaptureHigherEd\LaravelJira\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use CaptureHigherEd\LaravelJira\Exception\HttpClientException;

abstract class HttpApi
{
    protected Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    protected function httpGet(string $path, array $parameters = []): ResponseInterface
    {
        $response = $this->httpClient->get($path, ['query' => $parameters]);

        return $response;
    }

    protected function httpPost(string $path, array $parameters = []): ResponseInterface
    {
        $response = $this->httpClient->post($path, ['body' => json_encode($parameters)]);

        return $response;
    }

    protected function httpPut(string $path, array $parameters = []): ResponseInterface
    {
        $response = $this->httpClient->put($path, ['body' => json_encode($parameters)]);

        return $response;
    }

    protected function httpDelete(string $path): ResponseInterface
    {
        $response = $this->httpClient->delete($path);

        return $response;
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
            case 429:
                throw HttpClientException::tooManyRequests($response);
            default:
                throw HttpClientException::unknown($response);
        }
    }

    protected function hydrateResponse(ResponseInterface $response, ?string $class = null): mixed
    {
        $data = json_decode($response->getBody(), true);

        if (!in_array($response->getStatusCode(), [200, 201, 202, 204], true)) {
            $this->handleErrors($response);
        }

        if (!$class) {
            return $data;
        }

        $object = call_user_func([$class, 'make'], $data);

        return $object;
    }
}
