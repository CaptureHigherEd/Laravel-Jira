<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Projects extends Model
{
    /** @var array<int, Project> */
    private array $projects = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->projects = array_values(array_map(fn (array $item) => Project::make($item), $data));

        return $model;
    }

    /**
     * @return array<int, Project>
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (Project $p) => $p->toArray(), $this->projects);
    }
}
