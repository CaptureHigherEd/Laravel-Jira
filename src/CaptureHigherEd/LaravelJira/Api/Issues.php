<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Attachments;
use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\FieldMetas;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\IssueTypes;
use CaptureHigherEd\LaravelJira\Models\Search;

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
        $response = $this->httpPost(sprintf('issue/%s/comment', $issueId), $params);

        return $this->hydrateResponse($response, Comment::class);
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
}
