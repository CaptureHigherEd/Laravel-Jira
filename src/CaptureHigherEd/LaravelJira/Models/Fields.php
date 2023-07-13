<?php

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Exception\CustomFieldDoesNotExistException;

final class Fields implements ApiResponse
{
    private array $fields = [];

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
}
