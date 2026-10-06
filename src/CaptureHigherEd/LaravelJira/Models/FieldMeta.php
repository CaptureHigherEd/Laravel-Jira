<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class FieldMeta extends Model
{
    /**
     * @param  array<string, mixed>  $schema
     * @param  array<string>  $operations
     * @param  array<mixed>  $allowedValues
     */
    private function __construct(
        private string $fieldId = '',
        private string $name = '',
        private bool $required = false,
        private array $schema = [],
        private array $operations = [],
        private array $allowedValues = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            fieldId: $data['fieldId'] ?? '',
            name: $data['name'] ?? '',
            required: $data['required'] ?? false,
            schema: $data['schema'] ?? [],
            operations: $data['operations'] ?? [],
            allowedValues: $data['allowedValues'] ?? [],
        );
    }

    public function getFieldId(): string
    {
        return $this->fieldId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequired(): bool
    {
        return $this->required;
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
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @return array<mixed>
     */
    public function getAllowedValues(): array
    {
        return $this->allowedValues;
    }

    public function setFieldId(string $value): self
    {
        $this->fieldId = $value;

        return $this;
    }

    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setRequired(bool $value): self
    {
        $this->required = $value;

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
    public function setOperations(array $value): self
    {
        $this->operations = $value;

        return $this;
    }

    /**
     * @param  array<mixed>  $value
     */
    public function setAllowedValues(array $value): self
    {
        $this->allowedValues = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'fieldId' => $this->fieldId,
            'name' => $this->name,
            'required' => $this->required,
            'schema' => $this->schema,
            'operations' => $this->operations,
            'allowedValues' => $this->allowedValues,
        ];
    }
}
