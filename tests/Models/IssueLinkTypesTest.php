<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\IssueLinkType;
use CaptureHigherEd\LaravelJira\Models\IssueLinkTypes;
use PHPUnit\Framework\TestCase;

class IssueLinkTypesTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $types = IssueLinkTypes::make();

        $this->assertSame([], $types->getTypes(), 'IssueLinkTypes should default to an empty array when not provided');
    }

    public function test_make_hydrates_types(): void
    {
        $data = [
            'issueLinkTypes' => [
                ['id' => '1', 'name' => 'Blocks', 'inward' => 'is blocked by', 'outward' => 'blocks', 'self' => ''],
                ['id' => '2', 'name' => 'Cloners', 'inward' => 'is cloned by', 'outward' => 'clones', 'self' => ''],
            ],
        ];

        $types = IssueLinkTypes::make($data);

        $this->assertCount(2, $types->getTypes(), 'IssueLinkTypes should hydrate the correct number of types');
        $this->assertInstanceOf(IssueLinkType::class, $types->getTypes()[0], 'Each type should be hydrated as an IssueLinkType instance');
        $this->assertSame('Blocks', $types->getTypes()[0]->getName(), 'First type name should be hydrated correctly');
    }

    public function test_to_array(): void
    {
        $data = [
            'issueLinkTypes' => [
                ['id' => '1', 'name' => 'Blocks', 'inward' => 'is blocked by', 'outward' => 'blocks', 'self' => ''],
            ],
        ];

        $types = IssueLinkTypes::make($data);

        $this->assertCount(1, $types->toArray(), 'IssueLinkTypes::toArray() should return a flat list of type arrays');
        $this->assertSame('1', $types->toArray()[0]['id'], 'IssueLinkTypes::toArray() should preserve the type ID');
    }
}
