<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueLinkTypes extends Model
{
    /** @var array<int, IssueLinkType> */
    private array $types = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->types = array_map(
            fn (array $item) => IssueLinkType::make($item),
            $data['issueLinkTypes'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, IssueLinkType>
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (IssueLinkType $t) => $t->toArray(), $this->types);
    }
}
