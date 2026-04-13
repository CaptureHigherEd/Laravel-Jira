<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Issue implements ApiResponse
{
    const KEY = 'key';
    const ID = 'id';
    const FIELDS = 'fields';
    const SUMMARY = 'summary';
    const PROJECT = 'project';
    const DESCRIPTION = 'description';
    const ISSUETYPE = 'issuetype';
    const DUEDATE = 'duedate';
    const REPORTER = 'reporter';

    private array $fields = [];
    private string $key = '';
    private string $id = '';

    private function __construct()
    {
    }

    public static function make(?array $data = []): self
    {
        $model = new self();

        $model->setId($data[self::ID] ?? '');
        $model->setKey($data[self::KEY] ?? '');
        $model->setFields(array_filter($data[self::FIELDS] ?? []));

        return $model;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getSummary(): string|null
    {
        return $this->fields[self::SUMMARY] ?? null;
    }

    public function getDescription(): array|null
    {
        $description = $this->fields[self::DESCRIPTION] ?? null;

        if (is_array($description) && ! empty($description['content'])) {
            $description['content'] = self::sanitizeAdfNodes($description['content']);
        }

        return $description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLink(): string
    {
        return config('jira.domain') . '/browse/' . $this->key;
    }

    public function setFields($value): self
    {
        $this->fields = $value;

        return $this;
    }

    public function setField($field, $value): self
    {
        $this->fields[$field] = $value;

        return $this;
    }

    public function setId($value): self
    {
        $this->id = $value;

        return $this;
    }

    public function setKey($value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setProjectByKey($project): self
    {
        return $this->setField(self::PROJECT, ['key' => $project]);
    }

    public function setSummary($value): self
    {
        return $this->setField(self::SUMMARY, $value);
    }

    public function setDescription($value): self
    {
        return $this->setCustomFieldByContent(self::DESCRIPTION, $value);
    }

    public function setIssueType($value): self
    {
        return $this->setField(self::ISSUETYPE, $value);
    }

    public function setDueDate($value): self
    {
        return $this->setField(self::DUEDATE, $value);
    }

    public function setReporter($value): self
    {
        return $this->setCustomFieldById(self::REPORTER, $value);
    }

    public function setIssueTypeByName($value): self
    {
        return $this->setIssueType(["name" => $value]);
    }

    public function setCustomField($field, $value): self
    {
        return $this->setField($field, $value);
    }

    public function setCustomFieldById($field, $value): self
    {
        return $this->setCustomField($field, ["id" => $value]);
    }

    public function setCustomFieldByValue($field, $value, ?bool $is_multi_select = true): self
    {
        $value_array = $is_multi_select ? [["value" => $value]] : ["value" => $value];
        return $this->setCustomField($field, $value_array);
    }

    public function setCustomFieldByContent($field, $value): self
    {
        return $this->setField($field, [
            "content" => $value,
            "type" => "doc",
            "version" => 1,
        ]);
    }

    public function toArray(): array
    {
        return [
            'fields' => $this->fields,
        ];
    }

    /**
     * Recursively sanitize ADF nodes so empty "attrs" keys are objects, not arrays.
     *
     * PHP's json_decode converts JSON empty objects {} into empty PHP arrays [].
     * When re-encoded, these become JSON arrays [] instead of objects {}, which
     * Jira's ADF validator rejects as INVALID_INPUT. This converts empty attrs
     * back to stdClass so they encode as {}.
     */
    private static function sanitizeAdfNodes(array $nodes): array
    {
        foreach ($nodes as &$node) {
            if (isset($node['attrs']) && is_array($node['attrs']) && empty($node['attrs'])) {
                $node['attrs'] = new \stdClass();
            }

            if (! empty($node['content']) && is_array($node['content'])) {
                $node['content'] = self::sanitizeAdfNodes($node['content']);
            }

            if (! empty($node['marks']) && is_array($node['marks'])) {
                foreach ($node['marks'] as &$mark) {
                    if (isset($mark['attrs']) && is_array($mark['attrs']) && empty($mark['attrs'])) {
                        $mark['attrs'] = new \stdClass();
                    }
                }
                unset($mark);
            }
        }
        unset($node);

        return $nodes;
    }
}
