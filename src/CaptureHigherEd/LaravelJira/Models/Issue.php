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

    const CUSTOMFIELD_PARTNERWEBSITE = 'customfield_10056';
    const CUSTOMFIELD_ENGAGEURL = 'customfield_10057';
    const CUSTOMFIELD_POPULATIONSIZE = 'customfield_10060';
    const CUSTOMFIELD_POPULATIONTYPES = 'customfield_10061';
    const CUSTOMFIELD_POPULATIONNAMES = 'customfield_10062';
    const CUSTOMFIELD_REQUESTTYPE = 'customfield_10026';
    const CUSTOMFIELD_CLIENTNAME = 'customfield_10064';
    const CUSTOMFIELD_PHYSICALADDRESS = 'customfield_10070';

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
        return $this->setField(self::DESCRIPTION, [
            "content" => $value,
            "type" => "doc",
            "version" => 1,
        ]);
    }

    public function setIssueType($value): self
    {
        return $this->setField(self::ISSUETYPE, $value);
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

    public function setCustomFieldByValue($field, $value): self
    {
        return $this->setCustomField($field, ["value" => $value]);
    }

    public function toArray(): array
    {
        return [
            'fields' => $this->fields,
        ];
    }
}
