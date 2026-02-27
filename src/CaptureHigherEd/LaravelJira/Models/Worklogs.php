<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Worklogs extends Model
{
    /** @var array<int, Worklog> */
    private array $worklogs = [];

    private int $total = 0;

    private int $maxResults = 0;

    private int $startAt = 0;

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->total = $data['total'] ?? 0;
        $model->maxResults = $data['maxResults'] ?? 0;
        $model->startAt = $data['startAt'] ?? 0;
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

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function getStartAt(): int
    {
        return $this->startAt;
    }

    public function setTotal(int $value): self
    {
        $this->total = $value;

        return $this;
    }

    public function setMaxResults(int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setStartAt(int $value): self
    {
        $this->startAt = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'worklogs' => array_map(fn (Worklog $w) => $w->toArray(), $this->worklogs),
            'total' => $this->total,
            'maxResults' => $this->maxResults,
            'startAt' => $this->startAt,
        ];
    }
}
