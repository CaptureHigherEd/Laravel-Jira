<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class FieldMetas extends Model
{
    /** @var array<int, FieldMeta> */
    private array $fields = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->fields = array_map(
            fn (array $item) => FieldMeta::make($item),
            $data['fields'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, FieldMeta>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (FieldMeta $field) => $field->toArray(), $this->fields);
    }
}
