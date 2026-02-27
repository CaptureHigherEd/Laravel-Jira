<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Comment extends Model
{
    /**
     * @param  array<mixed>  $body
     * @param  array<string, mixed>  $visibility
     */
    private function __construct(
        private string $id = '',
        private array $body = [],
        private string $created = '',
        private string $updated = '',
        private string $self = '',
        private ?User $author = null,
        private ?User $updateAuthor = null,
        private bool $jsdPublic = true,
        private array $visibility = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            body: $data['body'] ?? [],
            created: $data['created'] ?? '',
            updated: $data['updated'] ?? '',
            self: $data['self'] ?? '',
            author: isset($data['author']) ? User::make($data['author']) : null,
            updateAuthor: isset($data['updateAuthor']) ? User::make($data['updateAuthor']) : null,
            jsdPublic: $data['jsdPublic'] ?? true,
            visibility: $data['visibility'] ?? [],
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array<mixed>
     */
    public function getBody(): array
    {
        return $this->body;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getUpdated(): string
    {
        return $this->updated;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getUpdateAuthor(): ?User
    {
        return $this->updateAuthor;
    }

    public function getJsdPublic(): bool
    {
        return $this->jsdPublic;
    }

    /**
     * @return array<string, mixed>
     */
    public function getVisibility(): array
    {
        return $this->visibility;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @param  array<mixed>  $value
     */
    public function setBody(array $value): self
    {
        $this->body = $value;

        return $this;
    }

    public function setCreated(string $value): self
    {
        $this->created = $value;

        return $this;
    }

    public function setUpdated(string $value): self
    {
        $this->updated = $value;

        return $this;
    }

    public function setSelf(string $value): self
    {
        $this->self = $value;

        return $this;
    }

    public function setAuthor(?User $value): self
    {
        $this->author = $value;

        return $this;
    }

    public function setUpdateAuthor(?User $value): self
    {
        $this->updateAuthor = $value;

        return $this;
    }

    public function setJsdPublic(bool $value): self
    {
        $this->jsdPublic = $value;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    public function setVisibility(array $value): self
    {
        $this->visibility = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created' => $this->created,
            'updated' => $this->updated,
            'self' => $this->self,
            'author' => $this->author?->toArray(),
            'updateAuthor' => $this->updateAuthor?->toArray(),
            'jsdPublic' => $this->jsdPublic,
            'visibility' => $this->visibility,
        ];
    }
}
