<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class IssueLink extends Model
{
    /**
     * @param  array<string, mixed>  $type
     * @param  array<string, mixed>  $inwardIssue
     * @param  array<string, mixed>  $outwardIssue
     */
    private function __construct(
        private string $id = '',
        private string $self = '',
        private array $type = [],
        private array $inwardIssue = [],
        private array $outwardIssue = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            self: $data['self'] ?? '',
            type: $data['type'] ?? [],
            inwardIssue: $data['inwardIssue'] ?? [],
            outwardIssue: $data['outwardIssue'] ?? [],
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    /**
     * @return array<string, mixed>
     */
    public function getType(): array
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>
     */
    public function getInwardIssue(): array
    {
        return $this->inwardIssue;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOutwardIssue(): array
    {
        return $this->outwardIssue;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

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
    public function setType(array $value): self
    {
        $this->type = $value;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public function setInwardIssue(array $value): self
    {
        $this->inwardIssue = $value;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public function setOutwardIssue(array $value): self
    {
        $this->outwardIssue = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'self' => $this->self,
            'type' => $this->type,
            'inwardIssue' => $this->inwardIssue,
            'outwardIssue' => $this->outwardIssue,
        ];
    }
}
