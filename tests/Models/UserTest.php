<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'accountId' => 'abc123',
            'displayName' => 'Jane Smith',
            'emailAddress' => 'jane@example.com',
            'active' => true,
        ];

        $user = User::make($data);

        $this->assertSame($data, $user->toArray());
    }

    public function test_make_with_empty_data(): void
    {
        $user = User::make();

        $this->assertSame('', $user->getKey());
        $this->assertSame('', $user->getName());
        $this->assertSame('', $user->getEmail());
        $this->assertFalse($user->getActive());
    }

    public function test_active_is_boolean(): void
    {
        $user = User::make(['active' => true]);

        $this->assertTrue($user->getActive());
        $this->assertIsBool($user->getActive());
    }
}
