<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

interface Paginated
{
    public function getTotal(): int;

    public function getMaxResults(): int;

    public function getStartAt(): int;

    public function setTotal(int $value): static;

    public function setMaxResults(int $value): static;

    public function setStartAt(int $value): static;

    public function hasMore(): bool;

    public function getNextStartAt(): int;
}
