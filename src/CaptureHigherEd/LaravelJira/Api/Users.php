<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Users as ModelsUsers;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-user-search/#api-rest-api-3-user-assignable-search-get
 */
class Users extends HttpApi
{
    /**
     * Get all users
     */
    public function index(array $params = ['maxResults' => 1000]): ModelsUsers
    {
        $response = $this->httpGet('users', $params);

        return $this->hydrateResponse($response, ModelsUsers::class);
    }

    /**
     * Get users assignable to a project
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-user-search/#api-rest-api-3-user-assignable-search-get
     */
    public function assignableForProject(string $projectKey, array $params = []): ModelsUsers
    {
        $response = $this->httpGet('user/assignable/search', array_merge([
            'project' => $projectKey,
            'maxResults' => 1000,
        ], $params));

        return $this->hydrateResponse($response, ModelsUsers::class);
    }
}
