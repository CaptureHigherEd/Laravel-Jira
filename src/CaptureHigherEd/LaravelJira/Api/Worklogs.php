<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Worklog;
use CaptureHigherEd\LaravelJira\Models\Worklogs as ModelsWorklogs;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-worklogs/#api-group-issue-worklogs
 */
class Worklogs extends HttpApi
{
    /**
     * Get all worklogs for an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-worklogs/#api-rest-api-3-issue-issueidorkey-worklog-get
     *
     * @param  array<string, mixed>  $params
     */
    public function index(string $issueId, array $params = []): ModelsWorklogs
    {
        $response = $this->httpGet(sprintf('issue/%s/worklog', $issueId), $params);

        return $this->hydrateResponse($response, ModelsWorklogs::class);
    }

    /**
     * Add a worklog to an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-worklogs/#api-rest-api-3-issue-issueidorkey-worklog-post
     *
     * @param  array<string, mixed>  $params
     */
    public function create(string $issueId, array $params = []): Worklog
    {
        $response = $this->httpPost(sprintf('issue/%s/worklog', $issueId), $params);

        return $this->hydrateResponse($response, Worklog::class);
    }

    /**
     * Update a worklog
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-worklogs/#api-rest-api-3-issue-issueidorkey-worklog-id-put
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $issueId, string $worklogId, array $params = []): Worklog
    {
        $response = $this->httpPut(sprintf('issue/%s/worklog/%s', $issueId, $worklogId), $params);

        return $this->hydrateResponse($response, Worklog::class);
    }

    /**
     * Delete a worklog
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-worklogs/#api-rest-api-3-issue-issueidorkey-worklog-id-delete
     *
     * @return array<mixed>
     */
    public function delete(string $issueId, string $worklogId): array
    {
        $response = $this->httpDelete(sprintf('issue/%s/worklog/%s', $issueId, $worklogId));

        return $this->hydrateResponse($response);
    }
}
