<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class User implements ApiResponse
{
    const NAME = 'displayName';

    const KEY = 'accountId';

    const EMAIL = 'emailAddress';

    const ACTIVE = 'active';

    const SELF = 'self';

    const ACCOUNT_TYPE = 'accountType';

    const TIME_ZONE = 'timeZone';

    const LOCALE = 'locale';

    const AVATAR_URLS = 'avatarUrls';

    private string $key = '';

    private string $email = '';

    private bool $active = false;

    private string $name = '';

    private string $self = '';

    private string $accountType = '';

    private string $timeZone = '';

    private string $locale = '';

    /** @var array<string, string> */
    private array $avatarUrls = [];

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
        $model->setSelf($data[self::SELF] ?? '');
        $model->setAccountType($data[self::ACCOUNT_TYPE] ?? '');
        $model->setTimeZone($data[self::TIME_ZONE] ?? '');
        $model->setLocale($data[self::LOCALE] ?? '');
        $model->setAvatarUrls($data[self::AVATAR_URLS] ?? []);

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
            self::KEY => $this->key,
            self::NAME => $this->name,
            self::EMAIL => $this->email,
            self::ACTIVE => $this->active,
            self::SELF => $this->self,
            self::ACCOUNT_TYPE => $this->accountType,
            self::TIME_ZONE => $this->timeZone,
            self::LOCALE => $this->locale,
            self::AVATAR_URLS => $this->avatarUrls,
        ];
    }
}
