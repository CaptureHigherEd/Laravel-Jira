<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueTypes extends Model
{
    /** @var array<int, IssueType> */
    private array $issueTypes = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

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
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (IssueType $issueType) => $issueType->toArray(), $this->issueTypes);
    }
}
