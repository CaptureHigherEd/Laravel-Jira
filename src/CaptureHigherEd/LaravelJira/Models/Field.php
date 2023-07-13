<?php

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Jira;

final class Field implements ApiResponse
{
    const CLAUSENAMES = 'clauseNames';
    const SEARCHABLE = 'searchable';
    const NAVIGABLE = 'navigable';
    const ORDERABLE = 'orderable';
    const SCHEMA = 'schema';
    const CUSTOM = 'custom';
    const NAME = 'name';
    const KEY = 'key';
    const ID = 'id';

    private string $key = '';
    private string $id = '';
    private string $name = '';
    private bool $custom = false;
    private bool $orderable = false;
    private bool $navigable = false;
    private bool $searchable = false;
    private array $clauseNames = [];
    private array $schema = [];

    private function __construct()
    {
    }

    public static function make(?array $data = []): self
    {
        $model = new self();

        $model->setId($data[self::ID] ?? '');
        $model->setKey($data[self::KEY] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setCustom($data[self::CUSTOM] ?? false);
        $model->setSearchable($data[self::SEARCHABLE] ?? false);
        $model->setOrderable($data[self::ORDERABLE] ?? false);
        $model->setNavigable($data[self::NAVIGABLE] ?? false);
        $model->setSchema($data[self::SCHEMA] ?? []);
        $model->setClauseNames($data[self::CLAUSENAMES] ?? []);

        return $model;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCustom()
    {
        return $this->custom;
    }

    public function getSearchable()
    {
        return $this->searchable;
    }

    public function getNavigable()
    {
        return $this->navigable;
    }

    public function getOrderable()
    {
        return $this->orderable;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function getClauseNames()
    {
        return $this->clauseNames;
    }

    public function getOptions(string $project_key, string $issue_type_name)
    {
        $jira = app(Jira::class);
        $meta = $jira->issues()->getCreateMeta(['expand' => 'projects.issuetypes.fields']);

        foreach ($meta['projects'] as $project) {
            if ($project['key'] == $project_key) {
                foreach ($project['issuetypes'] as $issue_type) {
                    if ($issue_type['name'] == $issue_type_name) {
                        foreach ($issue_type['fields'] as $field_key => $field) {
                            if ($field_key == $this->getKey()) {
                                return collect($field['allowedValues'])->pluck('value', 'value')->toArray();
                            }
                        }
                    }
                }
            }
        }

        return [];
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

    public function setName($value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setCustom(bool $value): self
    {
        $this->custom = $value;

        return $this;
    }

    public function setSearchable(bool $value): self
    {
        $this->searchable = $value;

        return $this;
    }

    public function setOrderable(bool $value): self
    {
        $this->orderable = $value;

        return $this;
    }

    public function setNavigable(bool $value): self
    {
        $this->navigable = $value;

        return $this;
    }

    public function setSchema(array $value): self
    {
        $this->schema = $value;

        return $this;
    }

    public function setClauseNames(array $value): self
    {
        $this->clauseNames = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [];
    }
}
