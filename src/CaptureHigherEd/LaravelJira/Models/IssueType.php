<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueType extends Model
{
    private function __construct(
        private string $id = '',
        private string $name = '',
        private string $description = '',
        private bool $subtask = false,
        private string $iconUrl = '',
        private string $self = '',
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
            subtask: $data['subtask'] ?? false,
            iconUrl: $data['iconUrl'] ?? '',
            self: $data['self'] ?? '',
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'subtask' => $this->subtask,
            'iconUrl' => $this->iconUrl,
            'self' => $this->self,
        ];
    }
}
