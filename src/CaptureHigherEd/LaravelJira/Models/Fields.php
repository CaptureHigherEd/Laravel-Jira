<?php

namespace CaptureHigherEd\LaravelJira\Models;

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

    public function toArray(): array
    {
        return [];
    }
}
