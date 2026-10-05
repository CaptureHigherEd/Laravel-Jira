<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Transition extends Model
{
    private function __construct(
        private string $id = '',
        private string $name = '',
        private ?Status $to = null,
        private bool $hasScreen = false,
        private bool $isGlobal = false,
        private bool $isInitial = false,
        private bool $isConditional = false,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? '',
            to: isset($data['to']) ? Status::make($data['to']) : null,
            hasScreen: $data['hasScreen'] ?? false,
            isGlobal: $data['isGlobal'] ?? false,
            isInitial: $data['isInitial'] ?? false,
            isConditional: $data['isConditional'] ?? false,
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

    public function getTo(): ?Status
    {
        return $this->to;
    }

    public function getHasScreen(): bool
    {
        return $this->hasScreen;
    }

    public function getIsGlobal(): bool
    {
        return $this->isGlobal;
    }

    public function getIsInitial(): bool
    {
        return $this->isInitial;
    }

    public function getIsConditional(): bool
    {
        return $this->isConditional;
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

    public function setTo(?Status $value): self
    {
        $this->to = $value;

        return $this;
    }

    public function setHasScreen(bool $value): self
    {
        $this->hasScreen = $value;

        return $this;
    }

    public function setIsGlobal(bool $value): self
    {
        $this->isGlobal = $value;

        return $this;
    }

    public function setIsInitial(bool $value): self
    {
        $this->isInitial = $value;

        return $this;
    }

    public function setIsConditional(bool $value): self
    {
        $this->isConditional = $value;

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
            'to' => $this->to?->toArray(),
            'hasScreen' => $this->hasScreen,
            'isGlobal' => $this->isGlobal,
            'isInitial' => $this->isInitial,
            'isConditional' => $this->isConditional,
        ];
    }
}
