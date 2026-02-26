<?php

namespace CaptureHigherEd\LaravelJira\Api;

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
}
