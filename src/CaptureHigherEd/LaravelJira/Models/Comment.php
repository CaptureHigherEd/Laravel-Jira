<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Comment implements ApiResponse
{
    const ID = 'id';

    const BODY = 'body';

    const CREATED = 'created';

    const UPDATED = 'updated';

    const SELF = 'self';

    const AUTHOR = 'author';

    const UPDATE_AUTHOR = 'updateAuthor';

    const JSD_PUBLIC = 'jsdPublic';

    const VISIBILITY = 'visibility';

    private string $id = '';

    /** @var array<mixed> */
    private array $body = [];

    private string $created = '';

    private string $updated = '';

    private string $self = '';

    private ?User $author = null;

    private ?User $updateAuthor = null;

    private bool $jsdPublic = true;

    /** @var array<string, mixed> */
    private array $visibility = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setId($data[self::ID] ?? '');
        $model->setBody($data[self::BODY] ?? []);
        $model->setCreated($data[self::CREATED] ?? '');
        $model->setUpdated($data[self::UPDATED] ?? '');
        $model->setSelf($data[self::SELF] ?? '');
        $model->setAuthor(isset($data[self::AUTHOR]) ? User::make($data[self::AUTHOR]) : null);
        $model->setUpdateAuthor(isset($data[self::UPDATE_AUTHOR]) ? User::make($data[self::UPDATE_AUTHOR]) : null);
        $model->setJsdPublic($data[self::JSD_PUBLIC] ?? true);
        $model->setVisibility($data[self::VISIBILITY] ?? []);

        return $model;
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
            self::ID => $this->id,
            self::BODY => $this->body,
            self::CREATED => $this->created,
            self::UPDATED => $this->updated,
            self::SELF => $this->self,
            self::AUTHOR => $this->author?->toArray(),
            self::UPDATE_AUTHOR => $this->updateAuthor?->toArray(),
            self::JSD_PUBLIC => $this->jsdPublic,
            self::VISIBILITY => $this->visibility,
        ];
    }
}
