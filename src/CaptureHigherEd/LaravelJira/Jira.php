<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira;

use CaptureHigherEd\LaravelJira\Http\HttpClientConfig;

class Jira
{
    private HttpClientConfig $config;

    public function __construct(
        HttpClientConnector $httpClient
    ) {
        $this->config = $httpClient->createConfig();
    }

    /**
     * @param  string  $apiKey  - in the format base64_encode('someone@gmail.com:token1234')
     * @param  string  $endpoint  - full path to the jira API
     */
    public static function create(string $apiKey, ?string $endpoint = null): self
    {
        $endpoint ??= config('jira.domain').'/rest/api/3/';
        $httpClient = (new HttpClientConnector)
            ->setApiKey($apiKey)
            ->setEndpoint($endpoint);

        return new self($httpClient);
    }

    public function issues(): Api\Issues
    {
        return new Api\Issues($this->config);
    }

    public function fields(): Api\Fields
    {
        return new Api\Fields($this->config);
    }

    public function users(): Api\Users
    {
        return new Api\Users($this->config);
    }

    public function projects(): Api\Projects
    {
        return new Api\Projects($this->config);
    }

    public function comments(): Api\Comments
    {
        return new Api\Comments($this->config);
    }

    public function worklogs(): Api\Worklogs
    {
        return new Api\Worklogs($this->config);
    }

    public function issueLinks(): Api\IssueLinks
    {
        return new Api\IssueLinks($this->config);
    }

    public function attachments(): Api\Attachments
    {
        return new Api\Attachments($this->config);
    }

    public function httpClient(): Api\HttpClient
    {
        return new Api\HttpClient($this->config);
    }
}
