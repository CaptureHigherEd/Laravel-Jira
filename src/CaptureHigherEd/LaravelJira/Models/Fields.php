<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Exception\CustomFieldDoesNotExistException;

final class Fields extends Model
{
    /** @var array<int, Field> */
    private array $fields = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->fields = array_values(array_map(fn (array $item) => Field::make($item), $data));

        return $model;
    }

    /**
     * @return array<int, Field>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array<int, Field>
     */
    public function getCustomFields(): array
    {
        return array_filter($this->fields, static function (Field $field): bool {
            return str_starts_with($field->getKey(), 'customfield_');
        });
    }

    public function getCustomFieldId(string $name): string
    {
        $field = current(array_filter($this->getCustomFields(), static function (Field $field) use ($name): bool {
            return $field->getName() === $name;
        }));

        if (! $field) {
            throw new CustomFieldDoesNotExistException($name);
        }

        return $field->getId();
    }

    public function getCustomField(string $name): Field
    {
        $field = current(array_filter($this->getCustomFields(), static function (Field $field) use ($name): bool {
            return $field->getName() === $name;
        }));

        if (! $field) {
            throw new CustomFieldDoesNotExistException($name);
        }

        return $field;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (Field $field) => $field->toArray(), $this->fields);
    }
}
