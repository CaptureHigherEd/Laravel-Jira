<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models\Concerns;

trait HasPagination
{
    private int $total = 0;

    private int $maxResults = 0;

    private int $startAt = 0;

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

    public function setTotal(int $value): static
    {
        $this->total = $value;

        return $this;
    }

    public function setMaxResults(int $value): static
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setStartAt(int $value): static
    {
        $this->startAt = $value;

        return $this;
    }

    public function hasMore(): bool
    {
        return ($this->startAt + $this->maxResults) < $this->total;
    }

    public function getNextStartAt(): int
    {
        return $this->startAt + $this->maxResults;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function hydratePagination(array $data): void
    {
        $this->total = $data['total'] ?? 0;
        $this->maxResults = $data['maxResults'] ?? 0;
        $this->startAt = $data['startAt'] ?? 0;
    }

    /**
     * @return array<string, int>
     */
    protected function paginationToArray(): array
    {
        return [
            'total' => $this->total,
            'maxResults' => $this->maxResults,
            'startAt' => $this->startAt,
        ];
    }
}
