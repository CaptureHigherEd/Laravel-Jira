<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\IssueType;
use CaptureHigherEd\LaravelJira\Models\IssueTypes;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use PHPUnit\Framework\TestCase;

class IssueTypesTest extends TestCase
{
    private function issueTypeData(): array
    {
        return [
            'id' => '10001',
            'name' => 'Bug',
            'description' => 'A bug',
            'subtask' => false,
            'iconUrl' => '',
            'self' => '',
        ];
    }

    public function test_implements_paginated(): void
    {
        $this->assertInstanceOf(Paginated::class, IssueTypes::make(), 'IssueTypes should implement the Paginated interface');
    }

    public function test_make_with_empty_data(): void
    {
        $collection = IssueTypes::make();

        $this->assertSame([], $collection->getIssueTypes(), 'IssueTypes should default to an empty array');
        $this->assertSame(0, $collection->getTotal(), 'IssueTypes total should default to 0');
        $this->assertSame(0, $collection->getMaxResults(), 'IssueTypes maxResults should default to 0');
        $this->assertSame(0, $collection->getStartAt(), 'IssueTypes startAt should default to 0');
    }

    public function test_make_hydrates_issue_types_and_pagination(): void
    {
        $data = [
            'issueTypes' => [$this->issueTypeData()],
            'total' => 5,
            'maxResults' => 50,
            'startAt' => 0,
        ];

        $collection = IssueTypes::make($data);

        $this->assertCount(1, $collection->getIssueTypes(), 'IssueTypes should hydrate the correct number of issue types');
        $this->assertInstanceOf(IssueType::class, $collection->getIssueTypes()[0], 'Each item should be hydrated as an IssueType instance');
        $this->assertSame(5, $collection->getTotal(), 'IssueTypes total should be hydrated correctly');
        $this->assertSame(50, $collection->getMaxResults(), 'IssueTypes maxResults should be hydrated correctly');
        $this->assertSame(0, $collection->getStartAt(), 'IssueTypes startAt should be hydrated correctly');
    }

    public function test_to_array_roundtrip(): void
    {
        $data = [
            'issueTypes' => [$this->issueTypeData()],
            'total' => 1,
            'maxResults' => 50,
            'startAt' => 0,
        ];

        $collection = IssueTypes::make($data);

        $this->assertSame($data, $collection->toArray(), 'IssueTypes::toArray() should return the same data passed to make()');
    }

    public function test_has_more(): void
    {
        $collection = IssueTypes::make(['issueTypes' => [], 'total' => 10, 'maxResults' => 5, 'startAt' => 0]);

        $this->assertTrue($collection->hasMore(), 'hasMore() should return true when more pages exist');
    }
}
