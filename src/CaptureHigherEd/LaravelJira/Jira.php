<?php

namespace CaptureHigherEd\LaravelJira;

use Psr\Http\Client\ClientInterface;
use CaptureHigherEd\LaravelJira\HttpClientConnector;

class Jira
{

    private ClientInterface $httpClient;

    public function __construct(
        HttpClientConnector $httpClient
    ) {
        $this->httpClient = $httpClient->createClient();
    }

    /**
     * @param  string $apiKey - in the format base64_encode('someone@gmail.com:token1234')
     * @param  string $endpoint - full path to the jira API
     * @return self
     */
    public static function create(string $apiKey, string $endpoint = 'https://capturehighered.atlassian.net/rest/api/3/'): self
    {
        $httpClient = (new HttpClientConnector())
            ->setApiKey($apiKey)
            ->setEndpoint($endpoint);

        return new self($httpClient);
    }

    /**
     * @return Api\Issues
     */
    public function issues(): Api\Issues
    {
        return new Api\Issues($this->httpClient);
    }

    /**
     * @return Api\IssueFields
     */
    public function fields(): Api\Fields
    {
        return new Api\Fields($this->httpClient);
    }
}
