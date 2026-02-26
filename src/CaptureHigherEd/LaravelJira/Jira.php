<?php

namespace CaptureHigherEd\LaravelJira;

use GuzzleHttp\ClientInterface;

class Jira
{
    private ClientInterface $httpClient;

    public function __construct(
        HttpClientConnector $httpClient
    ) {
        $this->httpClient = $httpClient->createClient();
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
        return new Api\Issues($this->httpClient);
    }

    public function fields(): Api\Fields
    {
        return new Api\Fields($this->httpClient);
    }

    public function users(): Api\Users
    {
        return new Api\Users($this->httpClient);
    }
}
