<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Attachments extends Model
{
    /** @var array<int, Attachment> */
    private array $attachments = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->attachments = array_values(array_map(fn (array $item) => Attachment::make($item), $data));

        return $model;
    }

    /**
     * @return array<int, Attachment>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (Attachment $a) => $a->toArray(), $this->attachments);
    }
}
