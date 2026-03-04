<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Attachment extends Model
{
    private function __construct(
        private string $id = '',
        private string $filename = '',
        private string $mimeType = '',
        private int $size = 0,
        private string $content = '',
        private string $self = '',
        private ?User $author = null,
        private string $created = '',
        private string $thumbnail = '',
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            filename: $data['filename'] ?? '',
            mimeType: $data['mimeType'] ?? '',
            size: (int) ($data['size'] ?? 0),
            content: $data['content'] ?? '',
            self: $data['self'] ?? '',
            author: isset($data['author']) ? User::make($data['author']) : null,
            created: $data['created'] ?? '',
            thumbnail: $data['thumbnail'] ?? '',
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

        return $this;
    }

    public function setFilename(string $value): self
    {
        $this->filename = $value;

        return $this;
    }

    public function setMimeType(string $value): self
    {
        $this->mimeType = $value;

        return $this;
    }

    public function setSize(int $value): self
    {
        $this->size = $value;

        return $this;
    }

    public function setContent(string $value): self
    {
        $this->content = $value;

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

    public function setCreated(string $value): self
    {
        $this->created = $value;

        return $this;
    }

    public function setThumbnail(string $value): self
    {
        $this->thumbnail = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
            'content' => $this->content,
            'self' => $this->self,
            'author' => $this->author?->toArray(),
            'created' => $this->created,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
