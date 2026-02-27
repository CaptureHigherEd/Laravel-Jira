<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Worklog;
use CaptureHigherEd\LaravelJira\Models\Worklogs;
use PHPUnit\Framework\TestCase;

class WorklogsTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $collection = Worklogs::make();

        $this->assertSame([], $collection->getWorklogs(), 'Worklogs should default to an empty worklogs array when not provided');
        $this->assertSame(0, $collection->getTotal(), 'Worklogs total should default to 0 when not provided');
        $this->assertSame(0, $collection->getMaxResults(), 'Worklogs maxResults should default to 0 when not provided');
        $this->assertSame(0, $collection->getStartAt(), 'Worklogs startAt should default to 0 when not provided');
    }

    public function test_make_hydrates_worklogs_and_pagination(): void
    {
        $data = [
            'worklogs' => [
                ['id' => '1', 'self' => '', 'author' => null, 'updateAuthor' => null, 'comment' => [], 'started' => '', 'timeSpent' => '1h', 'timeSpentSeconds' => 3600, 'issueId' => '10001', 'created' => '', 'updated' => ''],
            ],
            'total' => 5,
            'maxResults' => 20,
            'startAt' => 0,
        ];

        $collection = Worklogs::make($data);

        $this->assertCount(1, $collection->getWorklogs(), 'Worklogs should hydrate the correct number of worklogs');
        $this->assertInstanceOf(Worklog::class, $collection->getWorklogs()[0], 'Each worklog should be hydrated as a Worklog instance');
        $this->assertSame(5, $collection->getTotal(), 'Worklogs total should be hydrated correctly');
        $this->assertSame('1h', $collection->getWorklogs()[0]->getTimeSpent(), 'Worklog timeSpent should be hydrated correctly');
    }

    public function test_to_array_roundtrip(): void
    {
        $data = [
            'worklogs' => [
                ['id' => '1', 'self' => '', 'author' => null, 'updateAuthor' => null, 'comment' => [], 'started' => '', 'timeSpent' => '2h', 'timeSpentSeconds' => 7200, 'issueId' => '10001', 'created' => '', 'updated' => ''],
            ],
            'total' => 1,
            'maxResults' => 20,
            'startAt' => 0,
        ];

        $collection = Worklogs::make($data);

        $this->assertSame($data, $collection->toArray(), 'Worklogs::toArray() should return the same data passed to make()');
    }
}
