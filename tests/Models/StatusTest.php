<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '3',
            'name' => 'In Progress',
            'description' => 'Work is in progress.',
            'iconUrl' => 'https://example.com/icon.png',
            'self' => 'https://example.atlassian.net/rest/api/3/status/3',
            'statusCategory' => ['id' => 4, 'key' => 'indeterminate', 'name' => 'In Progress', 'colorName' => 'yellow'],
        ];

        $status = Status::make($data);

        $this->assertSame($data, $status->toArray(), 'Status::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $status = Status::make();

        $this->assertSame('', $status->getId(), 'Status ID should default to an empty string when not provided');
        $this->assertSame('', $status->getName(), 'Status name should default to an empty string when not provided');
        $this->assertSame('', $status->getDescription(), 'Status description should default to an empty string when not provided');
        $this->assertSame('', $status->getIconUrl(), 'Status iconUrl should default to an empty string when not provided');
        $this->assertSame('', $status->getSelf(), 'Status self URL should default to an empty string when not provided');
        $this->assertSame([], $status->getStatusCategory(), 'Status statusCategory should default to an empty array when not provided');
    }
}
