<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Search implements ApiResponse
{
    const ISSUES = 'issues';

    private array $issues = [];

    private function __construct()
    {
    }

    public static function make(array $data = []): self
    {
        $issues = [];

        if (isset($data[self::ISSUES])) {
            foreach ($data[self::ISSUES] as $item) {
                $issues[] = Issue::make($item);
            }
        }

        $model = new self();

        $model->issues = $issues;

        return $model;
    }

    public function getIssues(): array
    {
        return $this->issues;
    }

    public function toArray(): array
    {
        return [
            self::ISSUES => array_map(fn (Issue $issue) => $issue->toArray(), $this->issues),
        ];
    }
}
