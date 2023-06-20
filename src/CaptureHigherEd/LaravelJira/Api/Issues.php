<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\Search;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-group-issues
 */
class Issues extends HttpApi
{
    /**
     * Get all issues
     */
    public function index(array $params = [])
    {
        $response = $this->httpGet('search', $params);

        return $this->hydrateResponse($response, Search::class);
    }

    /**
     * Get an issue
     */
    public function show(string $issueId, array $params = []): Issue
    {
        $response = $this->httpGet(sprintf('issue/%s', $issueId), $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Create an issue
     */
    public function create(array $params = []): Issue
    {
        $response = $this->httpPost('issue', $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Update an issue
     */
    public function update(string $issueId, array $params = []): Issue
    {
        $response = $this->httpPut(sprintf('issue/%s', $issueId), $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Delete an issue
     */
    public function delete(string $issueId)
    {
        $response = $this->httpDelete(sprintf('issue/%s', $issueId));

        return $this->hydrateResponse($response);
    }
}
