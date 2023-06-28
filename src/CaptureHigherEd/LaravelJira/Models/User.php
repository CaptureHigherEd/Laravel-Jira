<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class User implements ApiResponse
{
    const NAME = 'displayName';
    const KEY = 'accountId';
    const EMAIL = 'emailAddress';
    const ACTIVE = 'active';

    private string $key = '';
    private string $email = '';
    private string $active = '';
    private string $name = '';

    private function __construct()
    {
    }

    public static function make(?array $data = []): self
    {
        $model = new self();

        $model->setKey($data[self::KEY] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setEmail($data[self::EMAIL] ?? '');
        $model->setActive($data[self::ACTIVE] ?? '');
        return $model;
    }


    public function getKey(): string
    {
        return $this->key;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getActive(): string
    {
        return $this->active;
    }

    public function setKey($value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setName($value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setEmail($value): self
    {
        $this->email = $value;

        return $this;
    }

    public function setActive($value): self
    {
        $this->active = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [];
    }
}
