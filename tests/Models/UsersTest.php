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
            ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true],
            ['accountId' => 'u2', 'displayName' => 'Bob', 'emailAddress' => 'bob@example.com', 'active' => true],
            ['accountId' => 'u3', 'displayName' => 'Charlie', 'emailAddress' => 'charlie@example.com', 'active' => false],
        ];
    }

    public function test_make_roundtrip(): void
    {
        $data = $this->userData();
        $users = Users::make($data);

        $this->assertCount(3, $users->getUsers());
        $this->assertContainsOnlyInstancesOf(User::class, $users->getUsers());
        $this->assertSame($data, $users->toArray());
    }

    public function test_make_with_empty_array(): void
    {
        $users = Users::make([]);

        $this->assertSame([], $users->getUsers());
        $this->assertSame([], $users->toArray());
    }

    public function test_get_active_users_filters_correctly(): void
    {
        $users = Users::make($this->userData());

        $active = $users->getActiveUsers();

        $this->assertCount(2, $active);
        foreach ($active as $user) {
            $this->assertTrue($user->getActive());
        }
    }

    public function test_get_active_users_returns_empty_when_none_active(): void
    {
        $users = Users::make([
            ['accountId' => 'u1', 'displayName' => 'A', 'emailAddress' => 'a@example.com', 'active' => false],
        ]);

        $this->assertSame([], array_values($users->getActiveUsers()));
    }
}
