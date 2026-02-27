<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Status;
use CaptureHigherEd\LaravelJira\Models\Transition;
use PHPUnit\Framework\TestCase;

class TransitionTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $transition = Transition::make();

        $this->assertSame('', $transition->getId(), 'Transition ID should default to an empty string when not provided');
        $this->assertSame('', $transition->getName(), 'Transition name should default to an empty string when not provided');
        $this->assertNull($transition->getTo(), 'Transition to-status should default to null when not provided');
        $this->assertFalse($transition->getHasScreen(), 'Transition hasScreen should default to false when not provided');
        $this->assertFalse($transition->getIsGlobal(), 'Transition isGlobal should default to false when not provided');
        $this->assertFalse($transition->getIsInitial(), 'Transition isInitial should default to false when not provided');
        $this->assertFalse($transition->getIsConditional(), 'Transition isConditional should default to false when not provided');
    }

    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '5',
            'name' => 'In Progress',
            'to' => [
                'id' => '3',
                'name' => 'In Progress',
                'description' => '',
                'iconUrl' => '',
                'self' => '',
                'statusCategory' => [],
            ],
            'hasScreen' => false,
            'isGlobal' => true,
            'isInitial' => false,
            'isConditional' => false,
        ];

        $transition = Transition::make($data);

        $this->assertSame($data, $transition->toArray(), 'Transition::toArray() should return the same data passed to make()');
    }

    public function test_to_status_is_hydrated(): void
    {
        $transition = Transition::make([
            'id' => '1',
            'name' => 'Done',
            'to' => ['id' => '10000', 'name' => 'Done', 'description' => '', 'iconUrl' => '', 'self' => '', 'statusCategory' => []],
        ]);

        $this->assertInstanceOf(Status::class, $transition->getTo(), 'Transition to-status should be hydrated as a Status instance');
        $this->assertSame('10000', $transition->getTo()?->getId(), 'Transition to-status ID should be hydrated correctly');
    }

    public function test_make_without_to_returns_null(): void
    {
        $transition = Transition::make(['id' => '1', 'name' => 'Done']);

        $this->assertNull($transition->getTo(), 'Transition to-status should be null when not present in data');
    }
}
