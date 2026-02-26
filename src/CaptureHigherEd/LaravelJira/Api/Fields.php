<?php

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Models\Field;
use CaptureHigherEd\LaravelJira\Models\Fields as ModelsFields;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-issue-fields/#api-group-issue-fields
 */
class Fields extends HttpApi
{
    /**
     * Get all fields
     */
    /**
     * @param  array<string, mixed>  $params
     */
    public function index(array $params = []): ModelsFields
    {
        $response = $this->httpGet('field', $params);

        return $this->hydrateResponse($response, ModelsFields::class);
    }

    /**
     * Get allowed values for a specific field within a project and issue type.
     *
     * @param  Field  $field  The field to look up options for
     * @param  string  $projectKey  The Jira project key (e.g. "CBE4")
     * @param  string  $issueTypeName  The issue type name (e.g. "Bug")
     * @return array<string, string> Map of value => value for the allowed options
     */
    public function getFieldOptions(Field $field, string $projectKey, string $issueTypeName): array
    {
        /** @var array<string, mixed> $meta */
        $meta = $this->hydrateResponse(
            $this->httpGet('issue/createmeta', ['expand' => 'projects.issuetypes.fields'])
        );

        foreach ($meta['projects'] as $project) {
            if ($project['key'] === $projectKey) {
                foreach ($project['issuetypes'] as $issueType) {
                    if ($issueType['name'] === $issueTypeName) {
                        foreach ($issueType['fields'] as $fieldKey => $fieldData) {
                            if ($fieldKey === $field->getKey()) {
                                /** @var array<mixed> $allowedValues */
                                $allowedValues = $fieldData['allowedValues'];

                                return collect($allowedValues)->pluck('value', 'value')->toArray();
                            }
                        }
                    }
                }
            }
        }

        return [];
    }
}
