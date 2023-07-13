<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Fields as ModelsFields;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-fields/#api-group-issue-fields
 */
class Fields extends HttpApi
{
    /**
     * Get all fields
     */
    public function index(array $params = []): ModelsFields
    {
        $response = $this->httpGet('field', $params);

        return $this->hydrateResponse($response, ModelsFields::class);
    }
}
