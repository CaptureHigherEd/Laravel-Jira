<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Attachments;
use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\FieldMetas;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\IssueTypes;
use CaptureHigherEd\LaravelJira\Models\Search;
use CaptureHigherEd\LaravelJira\Models\Transitions;
use CaptureHigherEd\LaravelJira\Models\Watchers;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-group-issues
 */
class Issues extends HttpApi
{
    /**
     * Get all issues
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-search/#api-rest-api-3-search-jql-get
     *
     * @param  array<string, mixed>  $params
     */
    public function index(array $params = []): Search
    {
        $response = $this->httpGet('search/jql', $params);

        return $this->hydrateResponse($response, Search::class);
    }

    /**
     * Get an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-issueidorkey-get
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
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-post
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
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-attachments/#api-rest-api-3-issue-issueidorkey-attachments-post
     *
     * @param  array<int, array<string, mixed>>  $params
     */
    public function attach(string $issueId, array $params = []): Attachments
    {
        $response = $this->httpPostWithAttachments(sprintf('issue/%s/attachments', $issueId), $params);

        return $this->hydrateResponse($response, Attachments::class);
    }

    /**
     * Add a comment
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-post
     *
     * @param  array<string, mixed>  $params
     */
    public function comment(string $issueId, array $params = []): Comment
    {
        return (new Comments($this->httpClient))->create($issueId, $params);
    }

    /**
     * Update an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-issueidorkey-put
     *
     * @param  array<string, mixed>  $params
     * @return array<mixed>
     */
    public function update(string $issueId, array $params = []): array
    {
        $response = $this->httpPut(sprintf('issue/%s', $issueId), $params);

        return $this->hydrateResponse($response);
    }

    /**
     * Get creation metadata
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-createmeta-get
     * @deprecated Use getCreateMetaIssueTypes() and getCreateMetaFields() instead.
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
     * Get issue types for a project's create metadata.
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-createmeta-projectidorkey-issuetypes-get
     *
     * @param  array<string, mixed>  $params  Query params (startAt, maxResults)
     */
    public function getCreateMetaIssueTypes(string $projectKey, array $params = []): IssueTypes
    {
        $response = $this->httpGet(sprintf('issue/createmeta/%s/issuetypes', $projectKey), $params);

        return $this->hydrateResponse($response, IssueTypes::class);
    }

    /**
     * Get field metadata for a specific project and issue type.
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-createmeta-projectidorkey-issuetypes-issuetypeid-get
     *
     * @param  array<string, mixed>  $params  Query params (startAt, maxResults)
     */
    public function getCreateMetaFields(string $projectKey, string $issueTypeId, array $params = []): FieldMetas
    {
        $response = $this->httpGet(
            sprintf('issue/createmeta/%s/issuetypes/%s', $projectKey, $issueTypeId),
            $params
        );

        return $this->hydrateResponse($response, FieldMetas::class);
    }

    /**
     * Delete an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-issueidorkey-delete
     *
     * @return array<mixed>
     */
    public function delete(string $issueId): array
    {
        $response = $this->httpDelete(sprintf('issue/%s', $issueId));

        return $this->hydrateResponse($response);
    }

    /**
     * Get available transitions for an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-issueidorkey-transitions-get
     */
    public function getTransitions(string $issueId): Transitions
    {
        $response = $this->httpGet(sprintf('issue/%s/transitions', $issueId));

        return $this->hydrateResponse($response, Transitions::class);
    }

    /**
     * Transition an issue to a new status
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-issueidorkey-transitions-post
     *
     * @param  array<string, mixed>  $params
     * @return array<mixed>
     */
    public function transition(string $issueId, array $params = []): array
    {
        $response = $this->httpPost(sprintf('issue/%s/transitions', $issueId), $params);

        return $this->hydrateResponse($response);
    }

    /**
     * Assign an issue to a user
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-issueidorkey-assignee-put
     *
     * @return array<mixed>
     */
    public function assign(string $issueId, string $accountId): array
    {
        $response = $this->httpPut(sprintf('issue/%s/assignee', $issueId), ['accountId' => $accountId]);

        return $this->hydrateResponse($response);
    }

    /**
     * Get watchers for an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-watchers/#api-rest-api-3-issue-issueidorkey-watchers-get
     */
    public function getWatchers(string $issueId): Watchers
    {
        $response = $this->httpGet(sprintf('issue/%s/watchers', $issueId));

        return $this->hydrateResponse($response, Watchers::class);
    }

    /**
     * Add a watcher to an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-watchers/#api-rest-api-3-issue-issueidorkey-watchers-post
     *
     * Jira expects the body to be a JSON-encoded string (e.g. "accountId"), not an object.
     *
     * @return array<mixed>
     */
    public function addWatcher(string $issueId, string $accountId): array
    {
        $response = $this->httpClient->request('POST', sprintf('issue/%s/watchers', $issueId), [
            'body' => json_encode($accountId),
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        return $this->hydrateResponse($response);
    }

    /**
     * Remove a watcher from an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-watchers/#api-rest-api-3-issue-issueidorkey-watchers-delete
     *
     * @return array<mixed>
     */
    public function removeWatcher(string $issueId, string $accountId): array
    {
        $response = $this->httpDelete(sprintf('issue/%s/watchers', $issueId), ['accountId' => $accountId]);

        return $this->hydrateResponse($response);
    }

    /**
     * Paginate through all search results
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-search/#api-rest-api-3-search-jql-get
     *
     * @param  array<string, mixed>  $params
     * @return \Generator<int, Search, mixed, void>
     */
    public function paginate(array $params = []): \Generator
    {
        return $this->paginateGet('search/jql', $params, Search::class);
    }

    /**
     * Paginate through issue types for a project's create metadata
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-createmeta-projectidorkey-issuetypes-get
     *
     * @param  array<string, mixed>  $params
     * @return \Generator<int, IssueTypes, mixed, void>
     */
    public function paginateCreateMetaIssueTypes(string $projectKey, array $params = []): \Generator
    {
        return $this->paginateGet(
            sprintf('issue/createmeta/%s/issuetypes', $projectKey),
            $params,
            IssueTypes::class
        );
    }

    /**
     * Paginate through field metadata for a specific project and issue type
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issues/#api-rest-api-3-issue-createmeta-projectidorkey-issuetypes-issuetypeid-get
     *
     * @param  array<string, mixed>  $params
     * @return \Generator<int, FieldMetas, mixed, void>
     */
    public function paginateCreateMetaFields(string $projectKey, string $issueTypeId, array $params = []): \Generator
    {
        return $this->paginateGet(
            sprintf('issue/createmeta/%s/issuetypes/%s', $projectKey, $issueTypeId),
            $params,
            FieldMetas::class
        );
    }
}
