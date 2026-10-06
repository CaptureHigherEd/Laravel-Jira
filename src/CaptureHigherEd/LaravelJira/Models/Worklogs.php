<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Models\Concerns\HasPagination;

final class Worklogs extends Model implements Paginated
{
    use HasPagination;

    /** @var array<int, Worklog> */
    private array $worklogs = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->hydratePagination($data);
        $model->worklogs = array_map(
            fn (array $item) => Worklog::make($item),
            $data['worklogs'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, Worklog>
     */
    public function getWorklogs(): array
    {
        return $this->worklogs;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'worklogs' => array_map(fn (Worklog $w) => $w->toArray(), $this->worklogs),
            ...$this->paginationToArray(),
        ];
    }
}
