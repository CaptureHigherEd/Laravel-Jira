<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Search implements ApiResponse
{
    const ISSUES = 'issues';

    /** @var array<int, Issue> */
    private array $issues = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $issues = [];

        if (isset($data[self::ISSUES])) {
            foreach ($data[self::ISSUES] as $item) {
                $issues[] = Issue::make($item);
            }
        }

        $model = new self;

        $model->issues = $issues;

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
            self::ISSUES => array_map(fn (Issue $issue) => $issue->toArray(), $this->issues),
        ];
    }
}
