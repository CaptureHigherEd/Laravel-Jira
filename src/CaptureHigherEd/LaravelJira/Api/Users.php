<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Users as ModelsUsers;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-fields/#api-group-issue-fields
 */
class Users extends HttpApi
{
    /**
     * Get all fields
     */
    public function index(array $params = ['maxResults' => 1000]): ModelsUsers
    {
        $response = $this->httpGet('users', $params);

        return $this->hydrateResponse($response, ModelsUsers::class);
    }
}
