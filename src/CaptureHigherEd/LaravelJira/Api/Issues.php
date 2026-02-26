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
     *
     * @param  array<string, mixed>  $params
     */
    public function index(array $params = []): Search
    {
        $response = $this->httpGet('search', $params);

        return $this->hydrateResponse($response, Search::class);
    }

    /**
     * Get an issue
     *
     * @param  array<string, mixed>  $params
     */
    public function show(string $issueId, array $params = []): Issue
    {
        $response = $this->httpGet(sprintf('issue/%s', $issueId), $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Create an issue
     *
     * @param  array<string, mixed>  $params
     */
    public function create(array $params = []): Issue
    {
        $response = $this->httpPost('issue', $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Attach a file
     *
     * @param  array<int, array<string, mixed>>  $params
     * @return array<mixed>
     */
    public function attach(string $issueId, array $params = []): array
    {
        $response = $this->httpPostWithAttachments(sprintf('issue/%s/attachments', $issueId), $params);

        return $this->hydrateResponse($response);
    }

    /**
     * Add a comment
     *
     * @param  array<string, mixed>  $params
     * @return array<mixed>
     */
    public function comment(string $issueId, array $params = []): array
    {
        $response = $this->httpPost(sprintf('issue/%s/comment', $issueId), $params);

        return $this->hydrateResponse($response);
    }

    /**
     * Update an issue
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $issueId, array $params = []): Issue
    {
        $response = $this->httpPut(sprintf('issue/%s', $issueId), $params);

        return $this->hydrateResponse($response, Issue::class);
    }

    /**
     * Get creation metadata
     *
     * @param  array<string, mixed>  $params
     * @return array<mixed>
     */
    public function getCreateMeta(array $params = []): array
    {
        $response = $this->httpGet('issue/createmeta', $params);

        return $this->hydrateResponse($response);
    }

    /**
     * Delete an issue
     *
     * @return array<mixed>
     */
    public function delete(string $issueId): array
    {
        $response = $this->httpDelete(sprintf('issue/%s', $issueId));

        return $this->hydrateResponse($response);
    }
}
