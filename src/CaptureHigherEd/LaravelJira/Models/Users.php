<?php

namespace CaptureHigherEd\LaravelJira\Models;


final class Users implements ApiResponse
{
    private array $users = [];

    private function __construct()
    {
    }

    public static function make(?array $data = []): self
    {
        $users = [];

        foreach ($data as $item) {
            $users[] = User::make($item);
        }

        $model = new self();

        $model->users = $users;

        return $model;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getActiveUsers()
    {
        return array_filter($this->users, static function (User $user): bool {
            return $user->getActive();
        });
    }

    public function toArray(): array
    {
        return [];
    }
}
