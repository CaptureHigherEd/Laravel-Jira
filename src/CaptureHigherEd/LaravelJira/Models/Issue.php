<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Issue extends Model
{
    const KEY = 'key';

    const ID = 'id';

    const SELF = 'self';

    const FIELDS = 'fields';

    const SUMMARY = 'summary';

    const DESCRIPTION = 'description';

    const STATUS = 'status';

    const ASSIGNEE = 'assignee';

    const REPORTER = 'reporter';

    const PRIORITY = 'priority';

    const ISSUETYPE = 'issuetype';

    const PROJECT = 'project';

    const LABELS = 'labels';

    const CREATED = 'created';

    const UPDATED = 'updated';

    const DUEDATE = 'duedate';

    const RESOLUTION = 'resolution';

    const RESOLUTIONDATE = 'resolutiondate';

    /** @var array<string, mixed> */
    private array $fields = [];

    private string $key = '';

    private string $id = '';

    private string $self = '';

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setId($data[self::ID] ?? '');
        $model->setKey($data[self::KEY] ?? '');
        $model->setSelf($data[self::SELF] ?? '');
        $model->setFields(array_filter($data[self::FIELDS] ?? [], fn ($v) => $v !== null));

        return $model;
    }

    /**
     * @return array<string, mixed>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getSummary(): ?string
    {
        return $this->fields[self::SUMMARY] ?? null;
    }

    /**
     * @return array<mixed>|null
     */
    public function getDescription(): ?array
    {
        return $this->fields[self::DESCRIPTION] ?? null;
    }

    public function getStatus(): ?Status
    {
        $data = $this->fields[self::STATUS] ?? null;

        return $data !== null ? Status::make($data) : null;
    }

    public function getAssignee(): ?User
    {
        $data = $this->fields[self::ASSIGNEE] ?? null;

        return $data !== null ? User::make($data) : null;
    }

    public function getReporter(): ?User
    {
        $data = $this->fields[self::REPORTER] ?? null;

        return $data !== null ? User::make($data) : null;
    }

    public function getPriority(): ?Priority
    {
        $data = $this->fields[self::PRIORITY] ?? null;

        return $data !== null ? Priority::make($data) : null;
    }

    public function getIssueType(): ?IssueType
    {
        $data = $this->fields[self::ISSUETYPE] ?? null;

        return $data !== null ? IssueType::make($data) : null;
    }

    public function getProject(): ?Project
    {
        $data = $this->fields[self::PROJECT] ?? null;

        return $data !== null ? Project::make($data) : null;
    }

    /**
     * @return array<string>|null
     */
    public function getLabels(): ?array
    {
        return $this->fields[self::LABELS] ?? null;
    }

    public function getCreated(): ?string
    {
        return $this->fields[self::CREATED] ?? null;
    }

    public function getUpdated(): ?string
    {
        return $this->fields[self::UPDATED] ?? null;
    }

    public function getDueDate(): ?string
    {
        return $this->fields[self::DUEDATE] ?? null;
    }

    public function getResolution(): ?Resolution
    {
        $data = $this->fields[self::RESOLUTION] ?? null;

        return $data !== null ? Resolution::make($data) : null;
    }

    public function getResolutionDate(): ?string
    {
        return $this->fields[self::RESOLUTIONDATE] ?? null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getLink(): string
    {
        return config('jira.domain').'/browse/'.$this->key;
    }

    /**
     * @param  array<string, mixed>  $value
     */
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

    public function setSelf(string $value): self
    {
        $this->self = $value;

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
        return $this->setIssueType(['name' => $value]);
    }

    public function setCustomField(string $field, mixed $value): self
    {
        return $this->setField($field, $value);
    }

    public function setCustomFieldById(string $field, string $value): self
    {
        return $this->setCustomField($field, ['id' => $value]);
    }

    public function setCustomFieldByValue(string $field, mixed $value, ?bool $is_multi_select = true): self
    {
        $value_array = $is_multi_select ? [['value' => $value]] : ['value' => $value];

        return $this->setCustomField($field, $value_array);
    }

    public function setCustomFieldByContent(string $field, mixed $value): self
    {
        return $this->setField($field, [
            'content' => $value,
            'type' => 'doc',
            'version' => 1,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'fields' => $this->fields,
        ];
    }
}
