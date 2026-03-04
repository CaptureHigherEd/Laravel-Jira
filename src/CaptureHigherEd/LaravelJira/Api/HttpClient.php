<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * Escape hatch for making arbitrary Jira API calls not yet covered by a specific Api class.
 *
 * Usage:
 *   $jira->httpClient()->httpGet('issue/KEY-1/subtasks');
 */
class HttpClient extends HttpApi
{
    public function httpGet(string $path, array $parameters = []): ResponseInterface
    {
        return parent::httpGet($path, $parameters);
    }

    public function httpPost(string $path, array $parameters = []): ResponseInterface
    {
        return parent::httpPost($path, $parameters);
    }

    public function httpPut(string $path, array $parameters = []): ResponseInterface
    {
        return parent::httpPut($path, $parameters);
    }

    public function httpDelete(string $path, array $parameters = []): ResponseInterface
    {
        return parent::httpDelete($path, $parameters);
    }

    public function httpPostRaw(string $path, string $body, string $contentType = 'application/json'): ResponseInterface
    {
        return parent::httpPostRaw($path, $body, $contentType);
    }
}
