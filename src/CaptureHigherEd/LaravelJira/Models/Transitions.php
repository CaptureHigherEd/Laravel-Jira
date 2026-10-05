<?php

namespace CaptureHigherEd\LaravelJira\Models;

final class Transitions extends Model
{
    /** @var array<int, Transition> */
    private array $transitions = [];

    private function __construct() {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        $model = new self;

        $model->transitions = array_map(
            fn (array $item) => Transition::make($item),
            $data['transitions'] ?? []
        );

        return $model;
    }

    /**
     * @return array<int, Transition>
     */
    public function getTransitions(): array
    {
        return $this->transitions;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (Transition $t) => $t->toArray(), $this->transitions);
    }
}
