<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Attachment implements ApiResponse
{
    const ID = 'id';

    const FILENAME = 'filename';

    const MIME_TYPE = 'mimeType';

    const SIZE = 'size';

    const CONTENT = 'content';

    const SELF = 'self';

    const AUTHOR = 'author';

    const CREATED = 'created';

    const THUMBNAIL = 'thumbnail';

    private string $id = '';

    private string $filename = '';

    private string $mimeType = '';

    private int $size = 0;

    private string $content = '';

    private string $self = '';

    private ?User $author = null;

    private string $created = '';

    private string $thumbnail = '';

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setId($data[self::ID] ?? '');
        $model->setFilename($data[self::FILENAME] ?? '');
        $model->setMimeType($data[self::MIME_TYPE] ?? '');
        $model->setSize((int) ($data[self::SIZE] ?? 0));
        $model->setContent($data[self::CONTENT] ?? '');
        $model->setSelf($data[self::SELF] ?? '');
        $model->setAuthor(isset($data[self::AUTHOR]) ? User::make($data[self::AUTHOR]) : null);
        $model->setCreated($data[self::CREATED] ?? '');
        $model->setThumbnail($data[self::THUMBNAIL] ?? '');

        return $model;
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
            self::ID => $this->id,
            self::FILENAME => $this->filename,
            self::MIME_TYPE => $this->mimeType,
            self::SIZE => $this->size,
            self::CONTENT => $this->content,
            self::SELF => $this->self,
            self::AUTHOR => $this->author?->toArray(),
            self::CREATED => $this->created,
            self::THUMBNAIL => $this->thumbnail,
        ];
    }
}
