<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Priority extends Model
{
    private function __construct(
        private string $id = '',
        private string $name = '',
        private string $description = '',
        private string $iconUrl = '',
        private string $self = '',
        private string $statusColor = '',
        private bool $isDefault = false,
        private int $avatarId = 0,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? '',
            description: $data['description'] ?? '',
            iconUrl: $data['iconUrl'] ?? '',
            self: $data['self'] ?? '',
            statusColor: $data['statusColor'] ?? '',
            isDefault: $data['isDefault'] ?? false,
            avatarId: (int) ($data['avatarId'] ?? 0),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getStatusColor(): string
    {
        return $this->statusColor;
    }

    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function getAvatarId(): int
    {
        return $this->avatarId;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

        return $this;
    }

    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setDescription(string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setIconUrl(string $value): self
    {
        $this->iconUrl = $value;

        return $this;
    }

    public function setSelf(string $value): self
    {
        $this->self = $value;

        return $this;
    }

    public function setStatusColor(string $value): self
    {
        $this->statusColor = $value;

        return $this;
    }

    public function setIsDefault(bool $value): self
    {
        $this->isDefault = $value;

        return $this;
    }

    public function setAvatarId(int $value): self
    {
        $this->avatarId = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'iconUrl' => $this->iconUrl,
            'self' => $this->self,
            'statusColor' => $this->statusColor,
            'isDefault' => $this->isDefault,
            'avatarId' => $this->avatarId,
        ];
    }
}
