<?php

namespace CaptureHigherEd\LaravelJira\Models;

use CaptureHigherEd\LaravelJira\Models\Concerns\HasPagination;

final class Comments extends Model implements Paginated
{
    use HasPagination;

    /** @var array<int, Comment> */
    private array $comments = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->hydratePagination($data);
        $model->comments = array_map(
            fn (array $item) => Comment::make($item),
            $data['comments'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, Comment>
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'comments' => array_map(fn (Comment $c) => $c->toArray(), $this->comments),
            ...$this->paginationToArray(),
        ];
    }
}
