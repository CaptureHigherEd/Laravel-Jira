<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Watchers extends Model
{
    /** @var array<int, User> */
    private array $watchers = [];

    private string $self = '';

    private bool $isWatching = false;

    private int $watchCount = 0;

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->self = $data['self'] ?? '';
        $model->isWatching = $data['isWatching'] ?? false;
        $model->watchCount = $data['watchCount'] ?? 0;
        $model->watchers = array_map(
            fn (array $item) => User::make($item),
            $data['watchers'] ?? []
        );

        return $model;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getIsWatching(): bool
    {
        return $this->isWatching;
    }

    public function getWatchCount(): int
    {
        return $this->watchCount;
    }

    /**
     * @return array<int, User>
     */
    public function getWatchers(): array
    {
        return $this->watchers;
    }

    public function setSelf(string $value): self
    {
        $this->self = $value;

        return $this;
    }

    public function setIsWatching(bool $value): self
    {
        $this->isWatching = $value;

        return $this;
    }

    public function setWatchCount(int $value): self
    {
        $this->watchCount = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'self' => $this->self,
            'isWatching' => $this->isWatching,
            'watchCount' => $this->watchCount,
            'watchers' => array_map(fn (User $u) => $u->toArray(), $this->watchers),
        ];
    }
}
