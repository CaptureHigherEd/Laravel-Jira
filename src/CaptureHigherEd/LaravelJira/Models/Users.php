<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Users implements ApiResponse
{
    /** @var array<int, User> */
    private array $users = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $users = [];

        foreach ($data as $item) {
            $users[] = User::make($item);
        }

        $model = new self;

        $model->users = $users;

        return $model;
    }

    /**
     * @return array<int, User>
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return array<int, User>
     */
    public function getActiveUsers(): array
    {
        return array_filter($this->users, static function (User $user): bool {
            return $user->getActive();
        });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (User $user) => $user->toArray(), $this->users);
    }
}
