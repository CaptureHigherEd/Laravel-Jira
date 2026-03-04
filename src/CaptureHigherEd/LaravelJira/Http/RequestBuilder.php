<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class RequestBuilder
{
    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    /**
     * Create a request without a body (GET, DELETE).
     */
    public function create(string $method, string $uri): RequestInterface
    {
        return $this->requestFactory->createRequest($method, $uri);
    }

    /**
     * Create a request with a JSON-encoded body (POST, PUT).
     *
     * @param  array<string, mixed>  $body
     */
    public function createWithJson(string $method, string $uri, array $body): RequestInterface
    {
        $stream = $this->streamFactory->createStream(json_encode($body) ?: '{}');

        return $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
    }

    /**
     * Create a request with a raw string body and explicit content type.
     * Used for the Jira addWatcher quirk where the body must be a JSON-encoded string.
     */
    public function createWithRawBody(string $method, string $uri, string $body, string $contentType): RequestInterface
    {
        $stream = $this->streamFactory->createStream($body);

        return $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Content-Type', $contentType)
            ->withBody($stream);
    }

    /**
     * Create a multipart request (file uploads).
     *
     * @param  array<int, array<string, mixed>>  $parts  Each part: ['name' => ..., 'contents' => ..., 'filename' => ...]
     */
    public function createWithMultipart(string $method, string $uri, array $parts): RequestInterface
    {
        $builder = new MultipartStreamBuilder($this->streamFactory);

        foreach ($parts as $part) {
            /** @var string $name */
            $name = $part['name'];
            /** @var string|\Psr\Http\Message\StreamInterface $contents */
            $contents = $part['contents'];
            $options = isset($part['filename']) ? ['filename' => $part['filename']] : [];
            $builder->addResource($name, $contents, $options);
        }

        $multipartStream = $builder->build();
        $boundary = $builder->getBoundary();

        return $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Content-Type', 'multipart/form-data; boundary="'.$boundary.'"')
            ->withBody($multipartStream);
    }
}
