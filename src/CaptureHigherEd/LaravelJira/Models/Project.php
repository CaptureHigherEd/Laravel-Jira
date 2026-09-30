<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Project implements ApiResponse
{
    const ID = 'id';

    const KEY = 'key';

    const NAME = 'name';

    const SELF = 'self';

    const PROJECT_TYPE_KEY = 'projectTypeKey';

    const SIMPLIFIED = 'simplified';

    const AVATAR_URLS = 'avatarUrls';

    private string $id = '';

    private string $key = '';

    private string $name = '';

    private string $self = '';

    private string $projectTypeKey = '';

    private bool $simplified = false;

    /** @var array<string, string> */
    private array $avatarUrls = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setId($data[self::ID] ?? '');
        $model->setKey($data[self::KEY] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setSelf($data[self::SELF] ?? '');
        $model->setProjectTypeKey($data[self::PROJECT_TYPE_KEY] ?? '');
        $model->setSimplified($data[self::SIMPLIFIED] ?? false);
        $model->setAvatarUrls($data[self::AVATAR_URLS] ?? []);

        return $model;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getProjectTypeKey(): string
    {
        return $this->projectTypeKey;
    }

    public function getSimplified(): bool
    {
        return $this->simplified;
    }

    /**
     * @return array<string, string>
     */
    public function getAvatarUrls(): array
    {
        return $this->avatarUrls;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

        return $this;
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

    public function setSelf(string $value): self
    {
        $this->self = $value;

        return $this;
    }

    public function setProjectTypeKey(string $value): self
    {
        $this->projectTypeKey = $value;

        return $this;
    }

    public function setSimplified(bool $value): self
    {
        $this->simplified = $value;

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
            self::ID => $this->id,
            self::KEY => $this->key,
            self::NAME => $this->name,
            self::SELF => $this->self,
            self::PROJECT_TYPE_KEY => $this->projectTypeKey,
            self::SIMPLIFIED => $this->simplified,
            self::AVATAR_URLS => $this->avatarUrls,
        ];
    }
}
