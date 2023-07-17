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
     * Attach a file
     */
    public function attach(string $issueId, array $params = []): Issue
    {
        $response = $this->httpPostWithAttachments(sprintf('issue/%s/attachments', $issueId), $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Add a comment
     */
    public function comment(string $issueId, array $params = []): Issue
    {
        $response = $this->httpPost(sprintf('issue/%s/comment', $issueId), $params);

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
     * Get creation metadata
     */
    public function getCreateMeta(array $params = [])
    {
        $response = $this->httpGet('issue/createmeta', $params);

        return $this->hydrateResponse($response);
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
