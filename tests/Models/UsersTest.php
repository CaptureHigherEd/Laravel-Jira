<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\User;
use CaptureHigherEd\LaravelJira\Models\Users;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    /** @return array<int, array<string, mixed>> */
    private function userData(): array
    {
        return [
            ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true, 'self' => '', 'accountType' => '', 'timeZone' => '', 'locale' => '', 'avatarUrls' => []],
            ['accountId' => 'u2', 'displayName' => 'Bob', 'emailAddress' => 'bob@example.com', 'active' => true, 'self' => '', 'accountType' => '', 'timeZone' => '', 'locale' => '', 'avatarUrls' => []],
            ['accountId' => 'u3', 'displayName' => 'Charlie', 'emailAddress' => 'charlie@example.com', 'active' => false, 'self' => '', 'accountType' => '', 'timeZone' => '', 'locale' => '', 'avatarUrls' => []],
        ];
    }

    // ── make & toArray ────────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $data = $this->userData();
        $users = Users::make($data);

        $this->assertCount(3, $users->getUsers(), 'Users collection should contain exactly 3 items matching the input array');
        $this->assertContainsOnlyInstancesOf(User::class, $users->getUsers(), 'All items in the Users collection should be hydrated as User instances');
        $this->assertSame($data, $users->toArray(), 'Users::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_array(): void
    {
        $users = Users::make([]);

        $this->assertSame([], $users->getUsers(), 'Users collection should be an empty array when constructed with no data');
        $this->assertSame([], $users->toArray(), 'Users::toArray() should return an empty array when no users are present');
    }

    // ── Filtering ─────────────────────────────────────────────────────────

    public function test_get_active_users_filters_correctly(): void
    {
        $users = Users::make($this->userData());

        $active = $users->getActiveUsers();

        $this->assertCount(2, $active, 'getActiveUsers() should return exactly 2 active users out of 3 total');
        foreach ($active as $user) {
            $this->assertTrue($user->getActive(), 'Each user returned by getActiveUsers() should have an active flag of true');
        }
    }

    public function test_get_active_users_returns_empty_when_none_active(): void
    {
        $users = Users::make([
            ['accountId' => 'u1', 'displayName' => 'A', 'emailAddress' => 'a@example.com', 'active' => false, 'self' => '', 'accountType' => '', 'timeZone' => '', 'locale' => '', 'avatarUrls' => []],
        ]);

        $this->assertSame([], array_values($users->getActiveUsers()), 'getActiveUsers() should return an empty array when all users are inactive');
    }
}
