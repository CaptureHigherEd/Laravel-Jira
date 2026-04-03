<?php

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Exception\CustomFieldDoesNotExistException;
use CaptureHigherEd\LaravelJira\Jira;

final class Fields implements ApiResponse
{
    private array $fields = [];

    private ?Jira $jira = null;

    private ?array $createMetaCache = null;

    private function __construct()
    {
    }

    public static function make(?array $data = []): self
    {
        $fields = [];

        foreach ($data as $item) {
            $fields[] = Field::make($item);
        }

        $model = new self();

        $model->fields = $fields;

        return $model;
    }

    public function setJira(Jira $jira): self
    {
        $this->jira = $jira;

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getCustomFields()
    {
        return array_filter($this->fields, static function (Field $field): bool {
            return str_starts_with($field->getKey(), 'customfield_');
        });
    }

    public function getCustomFieldId(string $name): string
    {
        $field = current(array_filter($this->getCustomFields(), static function (Field $field) use ($name): bool {
            return $field->getName() == $name;
        }));

        if (!$field) {
            throw new CustomFieldDoesNotExistException();
        }

        return $field->getId();
    }

    /**
     * Resolve a custom field ID by name, scoped to a specific project.
     *
     * Checks the create metadata to find the correct field ID for this project,
     * resolving duplicate field names across projects (e.g. "Order", "Client Name").
     * Looks at the specific issue type first, then all project issue types, then
     * falls back to the global field list for API-only fields not on any screen.
     */
    public function getCustomFieldIdForProject(string $name, string $projectKey, string $issueTypeName): string
    {
        // 1. Check the specific issue type's screen fields.
        $meta = $this->getCreateMeta($projectKey, $issueTypeName);
        foreach ($meta as $fieldKey => $fieldMeta) {
            if (($fieldMeta['name'] ?? '') === $name && str_starts_with($fieldKey, 'customfield_')) {
                return $fieldKey;
            }
        }

        // 2. Check all issue types in the project (field may be on a different screen).
        $allProjectFields = $this->getAllProjectFields($projectKey);
        foreach ($allProjectFields as $fieldKey => $fieldMeta) {
            if (($fieldMeta['name'] ?? '') === $name && str_starts_with($fieldKey, 'customfield_')) {
                return $fieldKey;
            }
        }

        // 3. Fall back to global field list (no duplicates expected for these).
        return $this->getCustomFieldId($name);
    }

    public function getCustomField(string $name): Field
    {
        $field = current(array_filter($this->getCustomFields(), static function (Field $field) use ($name): bool {
            return $field->getName() == $name;
        }));

        if (!$field) {
            throw new CustomFieldDoesNotExistException();
        }

        return $field;
    }

    public function toArray(): array
    {
        return [];
    }

    /**
     * Exclude runtime dependencies from serialization (e.g. when cached).
     */
    public function __sleep(): array
    {
        return ['fields'];
    }

    /**
     * Fetch and cache the create metadata fields for a project and issue type.
     */
    private function getCreateMeta(string $projectKey, string $issueTypeName): array
    {
        $cacheKey = $projectKey . ':' . $issueTypeName;

        if (isset($this->createMetaCache[$cacheKey])) {
            return $this->createMetaCache[$cacheKey];
        }

        $jira = $this->jira ?? app(Jira::class);
        $meta = $jira->issues()->getCreateMeta([
            'projectKeys' => $projectKey,
            'issuetypeNames' => $issueTypeName,
            'expand' => 'projects.issuetypes.fields',
        ]);

        $fields = [];
        foreach ($meta['projects'] ?? [] as $project) {
            if ($project['key'] === $projectKey) {
                foreach ($project['issuetypes'] ?? [] as $issueType) {
                    if ($issueType['name'] === $issueTypeName) {
                        $fields = $issueType['fields'] ?? [];
                        break 2;
                    }
                }
            }
        }

        $this->createMetaCache[$cacheKey] = $fields;

        return $fields;
    }

    /**
     * Fetch and cache the union of all fields across all issue types in a project.
     */
    private function getAllProjectFields(string $projectKey): array
    {
        $cacheKey = $projectKey . ':*';

        if (isset($this->createMetaCache[$cacheKey])) {
            return $this->createMetaCache[$cacheKey];
        }

        $jira = $this->jira ?? app(Jira::class);
        $meta = $jira->issues()->getCreateMeta([
            'projectKeys' => $projectKey,
            'expand' => 'projects.issuetypes.fields',
        ]);

        $merged = [];
        foreach ($meta['projects'] ?? [] as $project) {
            if ($project['key'] === $projectKey) {
                foreach ($project['issuetypes'] ?? [] as $issueType) {
                    foreach ($issueType['fields'] ?? [] as $fieldKey => $fieldMeta) {
                        $merged[$fieldKey] ??= $fieldMeta;
                    }
                }
            }
        }

        $this->createMetaCache[$cacheKey] = $merged;

        return $merged;
    }
}
