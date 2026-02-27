<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\User;
use CaptureHigherEd\LaravelJira\Models\Worklog;
use PHPUnit\Framework\TestCase;

class WorklogTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $worklog = Worklog::make();

        $this->assertSame('', $worklog->getId(), 'Worklog ID should default to an empty string when not provided');
        $this->assertSame('', $worklog->getSelf(), 'Worklog self URL should default to an empty string when not provided');
        $this->assertNull($worklog->getAuthor(), 'Worklog author should default to null when not provided');
        $this->assertNull($worklog->getUpdateAuthor(), 'Worklog updateAuthor should default to null when not provided');
        $this->assertSame([], $worklog->getComment(), 'Worklog comment should default to an empty array when not provided');
        $this->assertSame('', $worklog->getStarted(), 'Worklog started should default to an empty string when not provided');
        $this->assertSame('', $worklog->getTimeSpent(), 'Worklog timeSpent should default to an empty string when not provided');
        $this->assertSame(0, $worklog->getTimeSpentSeconds(), 'Worklog timeSpentSeconds should default to 0 when not provided');
        $this->assertSame('', $worklog->getIssueId(), 'Worklog issueId should default to an empty string when not provided');
        $this->assertSame('', $worklog->getCreated(), 'Worklog created should default to an empty string when not provided');
        $this->assertSame('', $worklog->getUpdated(), 'Worklog updated should default to an empty string when not provided');
    }

    public function test_make_roundtrip(): void
    {
        $userData = [
            'accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com',
            'active' => true, 'self' => '', 'accountType' => '', 'timeZone' => '', 'locale' => '', 'avatarUrls' => [],
        ];
        $data = [
            'id' => '10100',
            'self' => 'https://example.atlassian.net/rest/api/3/issue/KEY-1/worklog/10100',
            'author' => $userData,
            'updateAuthor' => $userData,
            'comment' => ['type' => 'doc', 'version' => 1, 'content' => []],
            'started' => '2024-01-01T09:00:00.000+0000',
            'timeSpent' => '2h',
            'timeSpentSeconds' => 7200,
            'issueId' => '10001',
            'created' => '2024-01-01T09:00:00.000+0000',
            'updated' => '2024-01-01T09:00:00.000+0000',
        ];

        $worklog = Worklog::make($data);

        $this->assertSame($data, $worklog->toArray(), 'Worklog::toArray() should return the same data passed to make()');
    }

    public function test_author_is_hydrated_as_user(): void
    {
        $worklog = Worklog::make([
            'author' => ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => '', 'active' => true],
        ]);

        $this->assertInstanceOf(User::class, $worklog->getAuthor(), 'Worklog author should be hydrated as a User instance');
        $this->assertSame('u1', $worklog->getAuthor()?->getKey(), 'Worklog author accountId should be hydrated correctly');
    }
}
