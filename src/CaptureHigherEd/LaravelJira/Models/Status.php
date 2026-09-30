<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Status implements ApiResponse
{
    const ID = 'id';

    const NAME = 'name';

    const DESCRIPTION = 'description';

    const ICON_URL = 'iconUrl';

    const SELF = 'self';

    const STATUS_CATEGORY = 'statusCategory';

    private string $id = '';

    private string $name = '';

    private string $description = '';

    private string $iconUrl = '';

    private string $self = '';

    /** @var array<string, mixed> */
    private array $statusCategory = [];

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
        $model->setStatusCategory($data[self::STATUS_CATEGORY] ?? []);

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

    /**
     * @return array<string, mixed>
     */
    public function getStatusCategory(): array
    {
        return $this->statusCategory;
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

    /**
     * @param  array<string, mixed>  $value
     */
    public function setStatusCategory(array $value): self
    {
        $this->statusCategory = $value;

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
            self::STATUS_CATEGORY => $this->statusCategory,
        ];
    }
}
