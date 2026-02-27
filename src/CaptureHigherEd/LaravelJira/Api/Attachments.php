<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Assert;
use CaptureHigherEd\LaravelJira\Models\Attachment;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-attachments/#api-group-issue-attachments
 */
class Attachments extends HttpApi
{
    /**
     * Get attachment metadata
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-attachments/#api-rest-api-3-attachment-attachmentid-get
     */
    public function show(string $attachmentId): Attachment
    {
        Assert::stringNotEmpty($attachmentId, 'Attachment ID must not be empty.');
        $response = $this->httpGet(sprintf('attachment/%s', $attachmentId));

        return $this->hydrateResponse($response, Attachment::class);
    }

    /**
     * Delete an attachment
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-attachments/#api-rest-api-3-attachment-attachmentid-delete
     *
     * @return array<mixed>
     */
    public function delete(string $attachmentId): array
    {
        Assert::stringNotEmpty($attachmentId, 'Attachment ID must not be empty.');
        $response = $this->httpDelete(sprintf('attachment/%s', $attachmentId));

        return $this->hydrateResponse($response);
    }

    /**
     * Get global attachment settings
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-attachments/#api-rest-api-3-attachment-meta-get
     *
     * @return array<mixed>
     */
    public function getMeta(): array
    {
        $response = $this->httpGet('attachment/meta');

        return $this->hydrateResponse($response);
    }
}
