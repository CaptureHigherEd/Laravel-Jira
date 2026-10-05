<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Project extends Model
{
    /**
     * @param  array<string, string>  $avatarUrls
     */
    private function __construct(
        private string $id = '',
        private string $key = '',
        private string $name = '',
        private string $self = '',
        private string $projectTypeKey = '',
        private bool $simplified = false,
        private array $avatarUrls = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            key: $data['key'] ?? '',
            name: $data['name'] ?? '',
            self: $data['self'] ?? '',
            projectTypeKey: $data['projectTypeKey'] ?? '',
            simplified: $data['simplified'] ?? false,
            avatarUrls: $data['avatarUrls'] ?? [],
        );
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
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'self' => $this->self,
            'projectTypeKey' => $this->projectTypeKey,
            'simplified' => $this->simplified,
            'avatarUrls' => $this->avatarUrls,
        ];
    }
}
