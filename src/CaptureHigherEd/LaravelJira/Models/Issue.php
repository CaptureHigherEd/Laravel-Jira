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

    public static function make(array $data = []): self
    {
        $model = new self();

        $model->setId($data[self::ID] ?? '');
        $model->setKey($data[self::KEY] ?? '');
        $model->setFields(array_filter($data[self::FIELDS] ?? [], fn ($v) => $v !== null));

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
        return $this->fields[self::DESCRIPTION] ?? null;
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

    public function setFields(array $value): self
    {
        $this->fields = $value;

        return $this;
    }

    public function setField(string $field, mixed $value): self
    {
        $this->fields[$field] = $value;

        return $this;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

        return $this;
    }

    public function setKey(string $value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setProjectByKey(string $project): self
    {
        return $this->setField(self::PROJECT, ['key' => $project]);
    }

    public function setSummary(string $value): self
    {
        return $this->setField(self::SUMMARY, $value);
    }

    public function setDescription(mixed $value): self
    {
        return $this->setCustomFieldByContent(self::DESCRIPTION, $value);
    }

    public function setIssueType(mixed $value): self
    {
        return $this->setField(self::ISSUETYPE, $value);
    }

    public function setDueDate(string $value): self
    {
        return $this->setField(self::DUEDATE, $value);
    }

    public function setReporter(string $value): self
    {
        return $this->setCustomFieldById(self::REPORTER, $value);
    }

    public function setIssueTypeByName(string $value): self
    {
        return $this->setIssueType(["name" => $value]);
    }

    public function setCustomField(string $field, mixed $value): self
    {
        return $this->setField($field, $value);
    }

    public function setCustomFieldById(string $field, string $value): self
    {
        return $this->setCustomField($field, ["id" => $value]);
    }

    public function setCustomFieldByValue(string $field, mixed $value, ?bool $is_multi_select = true): self
    {
        $value_array = $is_multi_select ? [["value" => $value]] : ["value" => $value];
        return $this->setCustomField($field, $value_array);
    }

    public function setCustomFieldByContent(string $field, mixed $value): self
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
}
