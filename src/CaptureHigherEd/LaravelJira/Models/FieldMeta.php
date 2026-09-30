<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class FieldMeta implements ApiResponse
{
    const FIELD_ID = 'fieldId';

    const NAME = 'name';

    const REQUIRED = 'required';

    const SCHEMA = 'schema';

    const OPERATIONS = 'operations';

    const ALLOWED_VALUES = 'allowedValues';

    private string $fieldId = '';

    private string $name = '';

    private bool $required = false;

    /** @var array<string, mixed> */
    private array $schema = [];

    /** @var array<string> */
    private array $operations = [];

    /** @var array<mixed> */
    private array $allowedValues = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setFieldId($data[self::FIELD_ID] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setRequired($data[self::REQUIRED] ?? false);
        $model->setSchema($data[self::SCHEMA] ?? []);
        $model->setOperations($data[self::OPERATIONS] ?? []);
        $model->setAllowedValues($data[self::ALLOWED_VALUES] ?? []);

        return $model;
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
            self::FIELD_ID => $this->fieldId,
            self::NAME => $this->name,
            self::REQUIRED => $this->required,
            self::SCHEMA => $this->schema,
            self::OPERATIONS => $this->operations,
            self::ALLOWED_VALUES => $this->allowedValues,
        ];
    }
}
