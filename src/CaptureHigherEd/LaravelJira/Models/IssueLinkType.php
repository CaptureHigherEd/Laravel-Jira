<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueLinkType extends Model
{
    private function __construct(
        private string $id = '',
        private string $name = '',
        private string $inward = '',
        private string $outward = '',
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
            inward: $data['inward'] ?? '',
            outward: $data['outward'] ?? '',
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

    public function getInward(): string
    {
        return $this->inward;
    }

    public function getOutward(): string
    {
        return $this->outward;
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

    public function setInward(string $value): self
    {
        $this->inward = $value;

        return $this;
    }

    public function setOutward(string $value): self
    {
        $this->outward = $value;

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
            'inward' => $this->inward,
            'outward' => $this->outward,
            'self' => $this->self,
        ];
    }
}
