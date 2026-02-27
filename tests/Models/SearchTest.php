<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\Paginated;
use CaptureHigherEd\LaravelJira\Models\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    // ── make & hydration ──────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $issue1 = ['id' => '1', 'key' => 'KEY-1', 'fields' => ['summary' => 'First']];
        $issue2 = ['id' => '2', 'key' => 'KEY-2', 'fields' => ['summary' => 'Second']];

        $search = Search::make(['issues' => [$issue1, $issue2]]);

        $this->assertCount(2, $search->getIssues(), 'Search result should contain exactly 2 issues matching the input array');
        $this->assertSame('KEY-1', $search->getIssues()[0]->getKey(), 'First issue key should match KEY-1');
        $this->assertSame('KEY-2', $search->getIssues()[1]->getKey(), 'Second issue key should match KEY-2');
    }

    public function test_make_with_empty_data(): void
    {
        $search = Search::make([]);

        $this->assertSame([], $search->getIssues(), 'Search result should contain an empty issues array when constructed with empty data');
    }

    public function test_make_without_issues_key(): void
    {
        $search = Search::make(['other' => 'data']);

        $this->assertSame([], $search->getIssues(), 'Search result should contain an empty issues array when the issues key is absent from the input data');
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
            $this->assertInstanceOf(Issue::class, $issue, 'Each item in the search results should be hydrated as an Issue instance');
        }
    }

    // ── pagination ────────────────────────────────────────────────────────

    public function test_implements_paginated(): void
    {
        $this->assertInstanceOf(Paginated::class, Search::make(), 'Search should implement the Paginated interface');
    }

    public function test_make_hydrates_pagination(): void
    {
        $search = Search::make([
            'issues' => [],
            'total' => 42,
            'maxResults' => 10,
            'startAt' => 20,
        ]);

        $this->assertSame(42, $search->getTotal(), 'Search should hydrate total from response data');
        $this->assertSame(10, $search->getMaxResults(), 'Search should hydrate maxResults from response data');
        $this->assertSame(20, $search->getStartAt(), 'Search should hydrate startAt from response data');
    }

    public function test_has_more(): void
    {
        $search = Search::make(['issues' => [], 'total' => 100, 'maxResults' => 50, 'startAt' => 0]);

        $this->assertTrue($search->hasMore(), 'hasMore() should return true when more pages exist');
    }

    public function test_has_more_false_on_last_page(): void
    {
        $search = Search::make(['issues' => [], 'total' => 100, 'maxResults' => 50, 'startAt' => 50]);

        $this->assertFalse($search->hasMore(), 'hasMore() should return false on the last page');
    }
}
