<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Priority implements ApiResponse
{
    const ID = 'id';

    const NAME = 'name';

    const DESCRIPTION = 'description';

    const ICON_URL = 'iconUrl';

    const SELF = 'self';

    const STATUS_COLOR = 'statusColor';

    const IS_DEFAULT = 'isDefault';

    const AVATAR_ID = 'avatarId';

    private string $id = '';

    private string $name = '';

    private string $description = '';

    private string $iconUrl = '';

    private string $self = '';

    private string $statusColor = '';

    private bool $isDefault = false;

    private int $avatarId = 0;

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setId($data[self::ID] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setDescription($data[self::DESCRIPTION] ?? '');
        $model->setIconUrl($data[self::ICON_URL] ?? '');
        $model->setSelf($data[self::SELF] ?? '');
        $model->setStatusColor($data[self::STATUS_COLOR] ?? '');
        $model->setIsDefault($data[self::IS_DEFAULT] ?? false);
        $model->setAvatarId((int) ($data[self::AVATAR_ID] ?? 0));

        return $model;
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
            self::ID => $this->id,
            self::NAME => $this->name,
            self::DESCRIPTION => $this->description,
            self::ICON_URL => $this->iconUrl,
            self::SELF => $this->self,
            self::STATUS_COLOR => $this->statusColor,
            self::IS_DEFAULT => $this->isDefault,
            self::AVATAR_ID => $this->avatarId,
        ];
    }
}
