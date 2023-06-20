<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Search implements ApiResponse
{
    private array $issues = [];

    private function __construct()
    {
    }

    public static function make(?array $data = []): self
    {
        $issues = [];

        if (isset($data['issues'])) {
            foreach ($data['issues'] as $item) {
                $issues[] = Issue::make($item);
            }
        }

        $model = new self();

        $model->issues = $issues;

        return $model;
    }

    public function getIssues()
    {
        return $this->issues;
    }

    public function toArray(): array
    {
        return [];
    }
}
