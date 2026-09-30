<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class FieldMetas implements ApiResponse
{
    const FIELDS = 'fields';

    /** @var array<int, FieldMeta> */
    private array $fields = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $fields = [];

        if (isset($data[self::FIELDS])) {
            foreach ($data[self::FIELDS] as $item) {
                $fields[] = FieldMeta::make($item);
            }
        }

        $model = new self;

        $model->fields = $fields;

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
