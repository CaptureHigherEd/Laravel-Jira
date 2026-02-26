<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    // ── make & toArray ────────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $data = [
            'accountId' => 'abc123',
            'displayName' => 'Jane Smith',
            'emailAddress' => 'jane@example.com',
            'active' => true,
        ];

        $user = User::make($data);

        $this->assertSame($data, $user->toArray(), 'User::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $user = User::make();

        $this->assertSame('', $user->getKey(), 'User account ID (key) should default to an empty string when not provided');
        $this->assertSame('', $user->getName(), 'User display name should default to an empty string when not provided');
        $this->assertSame('', $user->getEmail(), 'User email address should default to an empty string when not provided');
        $this->assertFalse($user->getActive(), 'User active flag should default to false when not provided');
    }

    // ── Type casting ──────────────────────────────────────────────────────

    public function test_active_is_boolean(): void
    {
        $user = User::make(['active' => true]);

        $this->assertTrue($user->getActive(), 'User active flag should be true when set to true');
        $this->assertIsBool($user->getActive(), 'User active flag should be stored and returned as a boolean');
    }
}
