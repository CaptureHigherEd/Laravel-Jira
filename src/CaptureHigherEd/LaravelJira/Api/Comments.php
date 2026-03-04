<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Assert;
use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\Comments as ModelsComments;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-group-issue-comments
 */
class Comments extends HttpApi
{
    /**
     * Get all comments for an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-get
     *
     * @param  array<string, mixed>  $params
     */
    public function index(string $issueId, array $params = []): ModelsComments
    {
        Assert::stringNotEmpty($issueId, 'Issue ID must not be empty.');
        $response = $this->httpGet(sprintf('issue/%s/comment', $issueId), $params);

        return $this->hydrateResponse($response, ModelsComments::class);
    }

    /**
     * Get a single comment
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-id-get
     */
    public function show(string $issueId, string $commentId): Comment
    {
        Assert::stringNotEmpty($issueId, 'Issue ID must not be empty.');
        Assert::stringNotEmpty($commentId, 'Comment ID must not be empty.');
        $response = $this->httpGet(sprintf('issue/%s/comment/%s', $issueId, $commentId));

        return $this->hydrateResponse($response, Comment::class);
    }

    /**
     * Add a comment to an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-post
     *
     * @param  array<string, mixed>  $params
     */
    public function create(string $issueId, array $params = []): Comment
    {
        Assert::stringNotEmpty($issueId, 'Issue ID must not be empty.');
        $response = $this->httpPost(sprintf('issue/%s/comment', $issueId), $params);

        return $this->hydrateResponse($response, Comment::class);
    }

    /**
     * Update a comment
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-id-put
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $issueId, string $commentId, array $params = []): Comment
    {
        Assert::stringNotEmpty($issueId, 'Issue ID must not be empty.');
        Assert::stringNotEmpty($commentId, 'Comment ID must not be empty.');
        $response = $this->httpPut(sprintf('issue/%s/comment/%s', $issueId, $commentId), $params);

        return $this->hydrateResponse($response, Comment::class);
    }

    /**
     * Delete a comment
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-id-delete
     *
     * @return array<mixed>
     */
    public function delete(string $issueId, string $commentId): array
    {
        Assert::stringNotEmpty($issueId, 'Issue ID must not be empty.');
        Assert::stringNotEmpty($commentId, 'Comment ID must not be empty.');
        $response = $this->httpDelete(sprintf('issue/%s/comment/%s', $issueId, $commentId));

        return $this->hydrateResponse($response);
    }

    /**
     * Paginate through all comments for an issue
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-comments/#api-rest-api-3-issue-issueidorkey-comment-get
     *
     * @param  array<string, mixed>  $params
     * @return \Generator<int, ModelsComments, mixed, void>
     */
    public function paginate(string $issueId, array $params = []): \Generator
    {
        Assert::stringNotEmpty($issueId, 'Issue ID must not be empty.');

        return $this->paginateGet(sprintf('issue/%s/comment', $issueId), $params, ModelsComments::class);
    }
}
