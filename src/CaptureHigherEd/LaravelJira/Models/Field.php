<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Field extends Model
{
    /**
     * @param  array<string>  $clauseNames
     * @param  array<string, mixed>  $schema
     * @param  array<string, mixed>  $scope
     */
    private function __construct(
        private string $key = '',
        private string $id = '',
        private string $name = '',
        private bool $custom = false,
        private bool $orderable = false,
        private bool $navigable = false,
        private bool $searchable = false,
        private array $clauseNames = [],
        private array $schema = [],
        private array $scope = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            key: $data['key'] ?? '',
            name: $data['name'] ?? '',
            custom: $data['custom'] ?? false,
            searchable: $data['searchable'] ?? false,
            orderable: $data['orderable'] ?? false,
            navigable: $data['navigable'] ?? false,
            schema: $data['schema'] ?? [],
            clauseNames: $data['clauseNames'] ?? [],
            scope: $data['scope'] ?? [],
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCustom(): bool
    {
        return $this->custom;
    }

    public function getSearchable(): bool
    {
        return $this->searchable;
    }

    public function getNavigable(): bool
    {
        return $this->navigable;
    }

    public function getOrderable(): bool
    {
        return $this->orderable;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * @return array<string>
     */
    public function getClauseNames(): array
    {
        return $this->clauseNames;
    }

    /**
     * @return array<string, mixed>
     */
    public function getScope(): array
    {
        return $this->scope;
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

    public function setName(string $value): self
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

    /**
     * @param  array<string, mixed>  $value
     */
    public function setSchema(array $value): self
    {
        $this->schema = $value;

        return $this;
    }

    /**
     * @param  array<string>  $value
     */
    public function setClauseNames(array $value): self
    {
        $this->clauseNames = $value;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public function setScope(array $value): self
    {
        $this->scope = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'custom' => $this->custom,
            'orderable' => $this->orderable,
            'navigable' => $this->navigable,
            'searchable' => $this->searchable,
            'clauseNames' => $this->clauseNames,
            'schema' => $this->schema,
            'scope' => $this->scope,
        ];
    }
}
