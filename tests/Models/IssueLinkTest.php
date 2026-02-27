<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\IssueLink;
use PHPUnit\Framework\TestCase;

class IssueLinkTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $link = IssueLink::make();

        $this->assertSame('', $link->getId(), 'IssueLink ID should default to an empty string when not provided');
        $this->assertSame('', $link->getSelf(), 'IssueLink self URL should default to an empty string when not provided');
        $this->assertSame([], $link->getType(), 'IssueLink type should default to an empty array when not provided');
        $this->assertSame([], $link->getInwardIssue(), 'IssueLink inwardIssue should default to an empty array when not provided');
        $this->assertSame([], $link->getOutwardIssue(), 'IssueLink outwardIssue should default to an empty array when not provided');
    }

    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '10000',
            'self' => 'https://example.atlassian.net/rest/api/3/issueLink/10000',
            'type' => ['id' => '1', 'name' => 'Blocks', 'inward' => 'is blocked by', 'outward' => 'blocks'],
            'inwardIssue' => ['id' => '10001', 'key' => 'KEY-1'],
            'outwardIssue' => ['id' => '10002', 'key' => 'KEY-2'],
        ];

        $link = IssueLink::make($data);

        $this->assertSame($data, $link->toArray(), 'IssueLink::toArray() should return the same data passed to make()');
    }
}
