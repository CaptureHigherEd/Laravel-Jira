<?php

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Models\Concerns\HasPagination;

final class IssueTypes extends Model implements Paginated
{
    use HasPagination;

    /** @var array<int, IssueType> */
    private array $issueTypes = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->hydratePagination($data);
        $model->issueTypes = array_map(
            fn (array $item) => IssueType::make($item),
            $data['issueTypes'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, IssueType>
     */
    public function getIssueTypes(): array
    {
        return $this->issueTypes;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'issueTypes' => array_map(fn (IssueType $issueType) => $issueType->toArray(), $this->issueTypes),
            ...$this->paginationToArray(),
        ];
    }
}
