<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Status extends Model
{
    /**
     * @param  array<string, mixed>  $statusCategory
     */
    private function __construct(
        private string $id = '',
        private string $name = '',
        private string $description = '',
        private string $iconUrl = '',
        private string $self = '',
        private array $statusCategory = [],
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
            statusCategory: $data['statusCategory'] ?? [],
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'iconUrl' => $this->iconUrl,
            'self' => $this->self,
            'statusCategory' => $this->statusCategory,
        ];
    }
}
