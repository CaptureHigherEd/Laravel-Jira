<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Resolution implements ApiResponse
{
    const ID = 'id';

    const NAME = 'name';

    const DESCRIPTION = 'description';

    const SELF = 'self';

    private string $id = '';

    private string $name = '';

    private string $description = '';

    private string $self = '';

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->setId($data[self::ID] ?? '');
        $model->setName($data[self::NAME] ?? '');
        $model->setDescription($data[self::DESCRIPTION] ?? '');
        $model->setSelf($data[self::SELF] ?? '');

        return $model;
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
            self::NAME => $this->name,
            self::DESCRIPTION => $this->description,
            self::SELF => $this->self,
        ];
    }
}
