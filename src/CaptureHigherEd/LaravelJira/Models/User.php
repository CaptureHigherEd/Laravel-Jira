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

    private bool $active = false;

    private string $name = '';

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setKey($data[self::KEY] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setEmail($data[self::EMAIL] ?? '');
        $model->setActive($data[self::ACTIVE] ?? false);

        return $model;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setKey(string $value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setEmail(string $value): self
    {
        $this->email = $value;

        return $this;
    }

    public function setActive(bool $value): self
    {
        $this->active = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            self::KEY => $this->key,
            self::NAME => $this->name,
            self::EMAIL => $this->email,
            self::ACTIVE => $this->active,
        ];
    }
}
