<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class User extends Model
{
    /**
     * @param  array<string, string>  $avatarUrls
     */
    private function __construct(
        private string $key = '',
        private string $email = '',
        private bool $active = false,
        private string $name = '',
        private string $self = '',
        private string $accountType = '',
        private string $timeZone = '',
        private string $locale = '',
        private array $avatarUrls = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            key: $data['accountId'] ?? '',
            email: $data['emailAddress'] ?? '',
            active: $data['active'] ?? false,
            name: $data['displayName'] ?? '',
            self: $data['self'] ?? '',
            accountType: $data['accountType'] ?? '',
            timeZone: $data['timeZone'] ?? '',
            locale: $data['locale'] ?? '',
            avatarUrls: $data['avatarUrls'] ?? [],
        );
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

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return array<string, string>
     */
    public function getAvatarUrls(): array
    {
        return $this->avatarUrls;
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

    public function setSelf(string $value): self
    {
        $this->self = $value;

        return $this;
    }

    public function setAccountType(string $value): self
    {
        $this->accountType = $value;

        return $this;
    }

    public function setTimeZone(string $value): self
    {
        $this->timeZone = $value;

        return $this;
    }

    public function setLocale(string $value): self
    {
        $this->locale = $value;

        return $this;
    }

    /**
     * @param  array<string, string>  $value
     */
    public function setAvatarUrls(array $value): self
    {
        $this->avatarUrls = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'accountId' => $this->key,
            'displayName' => $this->name,
            'emailAddress' => $this->email,
            'active' => $this->active,
            'self' => $this->self,
            'accountType' => $this->accountType,
            'timeZone' => $this->timeZone,
            'locale' => $this->locale,
            'avatarUrls' => $this->avatarUrls,
        ];
    }
}
