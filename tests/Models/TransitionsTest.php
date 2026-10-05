<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Transition;
use CaptureHigherEd\LaravelJira\Models\Transitions;
use PHPUnit\Framework\TestCase;

class TransitionsTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $transitions = Transitions::make();

        $this->assertSame([], $transitions->getTransitions(), 'Transitions should default to an empty array when not provided');
    }

    public function test_make_hydrates_transitions(): void
    {
        $data = [
            'transitions' => [
                ['id' => '1', 'name' => 'To Do', 'hasScreen' => false, 'isGlobal' => false, 'isInitial' => true, 'isConditional' => false],
                ['id' => '2', 'name' => 'In Progress', 'hasScreen' => false, 'isGlobal' => true, 'isInitial' => false, 'isConditional' => false],
            ],
        ];

        $transitions = Transitions::make($data);

        $this->assertCount(2, $transitions->getTransitions(), 'Transitions should hydrate the correct number of items');
        $this->assertInstanceOf(Transition::class, $transitions->getTransitions()[0], 'Each transition item should be a Transition instance');
        $this->assertSame('1', $transitions->getTransitions()[0]->getId(), 'First transition ID should be hydrated correctly');
    }

    public function test_to_array_returns_flat_list(): void
    {
        $data = [
            'transitions' => [
                ['id' => '5', 'name' => 'Done', 'hasScreen' => false, 'isGlobal' => true, 'isInitial' => false, 'isConditional' => false],
            ],
        ];

        $transitions = Transitions::make($data);
        $array = $transitions->toArray();

        $this->assertCount(1, $array, 'Transitions::toArray() should return a flat array of transition arrays');
        $this->assertSame('5', $array[0]['id'], 'Transitions::toArray() should preserve the transition ID');
    }
}
