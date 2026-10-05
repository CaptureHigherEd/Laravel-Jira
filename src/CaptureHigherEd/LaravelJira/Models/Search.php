<?php

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Models\Concerns\HasPagination;

final class Search extends Model implements Paginated
{
    use HasPagination;

    /** @var array<int, Issue> */
    private array $issues = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->hydratePagination($data);
        $model->issues = array_map(
            fn (array $item) => Issue::make($item),
            $data['issues'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, Issue>
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'issues' => array_map(fn (Issue $issue) => $issue->toArray(), $this->issues),
            ...$this->paginationToArray(),
        ];
    }
}
