<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Models\Concerns\HasPagination;

final class FieldMetas extends Model implements Paginated
{
    use HasPagination;

    /** @var array<int, FieldMeta> */
    private array $fields = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->hydratePagination($data);
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
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'fields' => array_map(fn (FieldMeta $field) => $field->toArray(), $this->fields),
            ...$this->paginationToArray(),
        ];
    }
}
