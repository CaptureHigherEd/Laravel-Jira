<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Assert;
use CaptureHigherEd\LaravelJira\Models\User;
use CaptureHigherEd\LaravelJira\Models\Users as ModelsUsers;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-users/#api-group-users
 */
class Users extends HttpApi
{
    /**
     * Get all users
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-users/#api-rest-api-3-users-get
     *
     * @param  array<string, mixed>  $params
     */
    public function index(array $params = ['maxResults' => 1000]): ModelsUsers
    {
        $response = $this->httpGet('users', $params);

        return $this->hydrateResponse($response, ModelsUsers::class);
    }

    /**
     * Get a user by account ID
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-users/#api-rest-api-3-user-get
     */
    public function show(string $accountId): User
    {
        Assert::stringNotEmpty($accountId, 'Account ID must not be empty.');
        $response = $this->httpGet('user', ['accountId' => $accountId]);

        return $this->hydrateResponse($response, User::class);
    }

    /**
     * Search for users
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-user-search/#api-rest-api-3-user-search-get
     *
     * @param  array<string, mixed>  $params
     */
    public function search(array $params = []): ModelsUsers
    {
        $response = $this->httpGet('user/search', $params);

        return $this->hydrateResponse($response, ModelsUsers::class);
    }

    /**
     * Get the currently authenticated user
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-myself/#api-rest-api-3-myself-get
     */
    public function myself(): User
    {
        $response = $this->httpGet('myself');

        return $this->hydrateResponse($response, User::class);
    }
}
