<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueType implements ApiResponse
{
    const ID = 'id';

    const NAME = 'name';

    const DESCRIPTION = 'description';

    const SUBTASK = 'subtask';

    const ICON_URL = 'iconUrl';

    const SELF = 'self';

    private string $id = '';

    private string $name = '';

    private string $description = '';

    private bool $subtask = false;

    private string $iconUrl = '';

    private string $self = '';

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
        $model->setSubtask($data[self::SUBTASK] ?? false);
        $model->setIconUrl($data[self::ICON_URL] ?? '');
        $model->setSelf($data[self::SELF] ?? '');

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

    public function getSubtask(): bool
    {
        return $this->subtask;
    }

    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    public function getSelf(): string
    {
        return $this->self;
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

    public function setSubtask(bool $value): self
    {
        $this->subtask = $value;

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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            self::ID => $this->id,
            self::NAME => $this->name,
            self::DESCRIPTION => $this->description,
            self::SUBTASK => $this->subtask,
            self::ICON_URL => $this->iconUrl,
            self::SELF => $this->self,
        ];
    }
}
