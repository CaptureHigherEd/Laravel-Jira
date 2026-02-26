<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Comment implements ApiResponse
{
    const ID = 'id';

    const BODY = 'body';

    const CREATED = 'created';

    const UPDATED = 'updated';

    const SELF = 'self';

    private string $id = '';

    /** @var array<mixed> */
    private array $body = [];

    private string $created = '';

    private string $updated = '';

    private string $self = '';

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
        ];
    }
}
