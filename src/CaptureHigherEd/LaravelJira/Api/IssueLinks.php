<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Assert;
use CaptureHigherEd\LaravelJira\Models\IssueLink;
use CaptureHigherEd\LaravelJira\Models\IssueLinkTypes;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-links/#api-group-issue-links
 */
class IssueLinks extends HttpApi
{
    /**
     * Create an issue link
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-links/#api-rest-api-3-issuelink-post
     *
     * @param  array<string, mixed>  $params
     * @return array<mixed>
     */
    public function create(array $params = []): array
    {
        $response = $this->httpPost('issueLink', $params);

        return $this->hydrateResponse($response);
    }

    /**
     * Get an issue link
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-links/#api-rest-api-3-issuelink-linkid-get
     */
    public function show(string $linkId): IssueLink
    {
        Assert::stringNotEmpty($linkId, 'Link ID must not be empty.');
        $response = $this->httpGet(sprintf('issueLink/%s', $linkId));

        return $this->hydrateResponse($response, IssueLink::class);
    }

    /**
     * Delete an issue link
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-links/#api-rest-api-3-issuelink-linkid-delete
     *
     * @return array<mixed>
     */
    public function delete(string $linkId): array
    {
        Assert::stringNotEmpty($linkId, 'Link ID must not be empty.');
        $response = $this->httpDelete(sprintf('issueLink/%s', $linkId));

        return $this->hydrateResponse($response);
    }

    /**
     * Get all issue link types
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-link-types/#api-rest-api-3-issuelinktype-get
     */
    public function getTypes(): IssueLinkTypes
    {
        $response = $this->httpGet('issueLinkType');

        return $this->hydrateResponse($response, IssueLinkTypes::class);
    }
}
