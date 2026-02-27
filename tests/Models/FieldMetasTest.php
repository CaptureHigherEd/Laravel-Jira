<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\FieldMeta;
use CaptureHigherEd\LaravelJira\Models\FieldMetas;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use PHPUnit\Framework\TestCase;

class FieldMetasTest extends TestCase
{
    private function fieldMetaData(): array
    {
        return [
            'fieldId' => 'summary',
            'name' => 'Summary',
            'required' => true,
            'schema' => ['type' => 'string'],
            'operations' => ['set'],
            'allowedValues' => [],
        ];
    }

    public function test_implements_paginated(): void
    {
        $this->assertInstanceOf(Paginated::class, FieldMetas::make(), 'FieldMetas should implement the Paginated interface');
    }

    public function test_make_with_empty_data(): void
    {
        $collection = FieldMetas::make();

        $this->assertSame([], $collection->getFields(), 'FieldMetas should default to an empty array');
        $this->assertSame(0, $collection->getTotal(), 'FieldMetas total should default to 0');
        $this->assertSame(0, $collection->getMaxResults(), 'FieldMetas maxResults should default to 0');
        $this->assertSame(0, $collection->getStartAt(), 'FieldMetas startAt should default to 0');
    }

    public function test_make_hydrates_fields_and_pagination(): void
    {
        $data = [
            'fields' => [$this->fieldMetaData()],
            'total' => 3,
            'maxResults' => 50,
            'startAt' => 0,
        ];

        $collection = FieldMetas::make($data);

        $this->assertCount(1, $collection->getFields(), 'FieldMetas should hydrate the correct number of fields');
        $this->assertInstanceOf(FieldMeta::class, $collection->getFields()[0], 'Each item should be hydrated as a FieldMeta instance');
        $this->assertSame(3, $collection->getTotal(), 'FieldMetas total should be hydrated correctly');
        $this->assertSame(50, $collection->getMaxResults(), 'FieldMetas maxResults should be hydrated correctly');
        $this->assertSame(0, $collection->getStartAt(), 'FieldMetas startAt should be hydrated correctly');
    }

    public function test_to_array_roundtrip(): void
    {
        $data = [
            'fields' => [$this->fieldMetaData()],
            'total' => 1,
            'maxResults' => 50,
            'startAt' => 0,
        ];

        $collection = FieldMetas::make($data);

        $this->assertSame($data, $collection->toArray(), 'FieldMetas::toArray() should return the same data passed to make()');
    }

    public function test_has_more(): void
    {
        $collection = FieldMetas::make(['fields' => [], 'total' => 20, 'maxResults' => 10, 'startAt' => 0]);

        $this->assertTrue($collection->hasMore(), 'hasMore() should return true when more pages exist');
    }
}
