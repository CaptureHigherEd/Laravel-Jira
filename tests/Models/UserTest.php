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
            'self' => 'https://example.atlassian.net/rest/api/3/user?accountId=abc123',
            'accountType' => 'atlassian',
            'timeZone' => 'Australia/Sydney',
            'locale' => 'en_AU',
            'avatarUrls' => ['16x16' => 'https://example.com/16.png', '48x48' => 'https://example.com/48.png'],
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
        $this->assertSame('', $user->getSelf(), 'User self URL should default to an empty string when not provided');
        $this->assertSame('', $user->getAccountType(), 'User accountType should default to an empty string when not provided');
        $this->assertSame('', $user->getTimeZone(), 'User timeZone should default to an empty string when not provided');
        $this->assertSame('', $user->getLocale(), 'User locale should default to an empty string when not provided');
        $this->assertSame([], $user->getAvatarUrls(), 'User avatarUrls should default to an empty array when not provided');
    }

    // ── Type casting ──────────────────────────────────────────────────────

    public function test_active_is_boolean(): void
    {
        $user = User::make(['active' => true]);

        $this->assertTrue($user->getActive(), 'User active flag should be true when set to true');
        $this->assertIsBool($user->getActive(), 'User active flag should be stored and returned as a boolean');
    }
}
