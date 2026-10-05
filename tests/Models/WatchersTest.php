<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\User;
use CaptureHigherEd\LaravelJira\Models\Watchers;
use PHPUnit\Framework\TestCase;

class WatchersTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $watchers = Watchers::make();

        $this->assertSame('', $watchers->getSelf(), 'Watchers self URL should default to an empty string when not provided');
        $this->assertFalse($watchers->getIsWatching(), 'Watchers isWatching should default to false when not provided');
        $this->assertSame(0, $watchers->getWatchCount(), 'Watchers watchCount should default to 0 when not provided');
        $this->assertSame([], $watchers->getWatchers(), 'Watchers list should default to an empty array when not provided');
    }

    public function test_make_roundtrip(): void
    {
        $userData = [
            'accountId' => 'u1',
            'displayName' => 'Alice',
            'emailAddress' => 'alice@example.com',
            'active' => true,
            'self' => '',
            'accountType' => '',
            'timeZone' => '',
            'locale' => '',
            'avatarUrls' => [],
        ];
        $data = [
            'self' => 'https://example.atlassian.net/rest/api/3/issue/KEY-1/watchers',
            'isWatching' => true,
            'watchCount' => 1,
            'watchers' => [$userData],
        ];

        $watchers = Watchers::make($data);

        $this->assertSame($data, $watchers->toArray(), 'Watchers::toArray() should return the same data passed to make()');
    }

    public function test_watchers_are_hydrated_as_users(): void
    {
        $data = [
            'self' => '',
            'isWatching' => false,
            'watchCount' => 2,
            'watchers' => [
                ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => '', 'active' => true],
                ['accountId' => 'u2', 'displayName' => 'Bob', 'emailAddress' => '', 'active' => false],
            ],
        ];

        $watchers = Watchers::make($data);

        $this->assertCount(2, $watchers->getWatchers(), 'Watchers list should contain the correct number of users');
        $this->assertInstanceOf(User::class, $watchers->getWatchers()[0], 'Each watcher should be a User instance');
        $this->assertSame('u1', $watchers->getWatchers()[0]->getKey(), 'First watcher accountId should be hydrated correctly');
    }
}
