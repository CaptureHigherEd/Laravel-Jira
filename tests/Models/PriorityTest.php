<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Priority;
use PHPUnit\Framework\TestCase;

class PriorityTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '2',
            'name' => 'High',
            'description' => 'High priority.',
            'iconUrl' => 'https://example.com/high.png',
            'self' => 'https://example.atlassian.net/rest/api/3/priority/2',
            'statusColor' => '#FF0000',
            'isDefault' => false,
            'avatarId' => 10100,
        ];

        $priority = Priority::make($data);

        $this->assertSame($data, $priority->toArray(), 'Priority::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $priority = Priority::make();

        $this->assertSame('', $priority->getId(), 'Priority ID should default to an empty string when not provided');
        $this->assertSame('', $priority->getName(), 'Priority name should default to an empty string when not provided');
        $this->assertSame('', $priority->getDescription(), 'Priority description should default to an empty string when not provided');
        $this->assertSame('', $priority->getIconUrl(), 'Priority iconUrl should default to an empty string when not provided');
        $this->assertSame('', $priority->getSelf(), 'Priority self URL should default to an empty string when not provided');
        $this->assertSame('', $priority->getStatusColor(), 'Priority statusColor should default to an empty string when not provided');
        $this->assertFalse($priority->getIsDefault(), 'Priority isDefault should default to false when not provided');
        $this->assertSame(0, $priority->getAvatarId(), 'Priority avatarId should default to 0 when not provided');
    }

    public function test_avatar_id_cast_to_int(): void
    {
        $priority = Priority::make(['avatarId' => '10100']);

        $this->assertSame(10100, $priority->getAvatarId(), 'Priority avatarId should be cast to integer even when provided as a string');
        $this->assertIsInt($priority->getAvatarId(), 'Priority avatarId should be stored and returned as an integer');
    }
}
