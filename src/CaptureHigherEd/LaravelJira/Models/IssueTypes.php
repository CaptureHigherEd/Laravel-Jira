<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueTypes implements ApiResponse
{
    const ISSUE_TYPES = 'issueTypes';

    /** @var array<int, IssueType> */
    private array $issueTypes = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $issueTypes = [];

        if (isset($data[self::ISSUE_TYPES])) {
            foreach ($data[self::ISSUE_TYPES] as $item) {
                $issueTypes[] = IssueType::make($item);
            }
        }

        $model = new self;

        $model->issueTypes = $issueTypes;

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
