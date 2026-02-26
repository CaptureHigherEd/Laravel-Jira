<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $issue1 = ['id' => '1', 'key' => 'KEY-1', 'fields' => ['summary' => 'First']];
        $issue2 = ['id' => '2', 'key' => 'KEY-2', 'fields' => ['summary' => 'Second']];

        $search = Search::make(['issues' => [$issue1, $issue2]]);

        $this->assertCount(2, $search->getIssues());
        $this->assertSame('KEY-1', $search->getIssues()[0]->getKey());
        $this->assertSame('KEY-2', $search->getIssues()[1]->getKey());
    }

    public function test_make_with_empty_data(): void
    {
        $search = Search::make([]);

        $this->assertSame([], $search->getIssues());
    }

    public function test_make_without_issues_key(): void
    {
        $search = Search::make(['other' => 'data']);

        $this->assertSame([], $search->getIssues());
    }

    public function test_make_hydrates_each_issue(): void
    {
        $search = Search::make([
            'issues' => [
                ['id' => '1', 'key' => 'KEY-1', 'fields' => []],
                ['id' => '2', 'key' => 'KEY-2', 'fields' => []],
            ],
        ]);

        foreach ($search->getIssues() as $issue) {
            $this->assertInstanceOf(Issue::class, $issue);
        }
    }
}
