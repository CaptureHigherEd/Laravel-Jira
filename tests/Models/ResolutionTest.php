<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Resolution;
use PHPUnit\Framework\TestCase;

class ResolutionTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '1',
            'name' => 'Done',
            'description' => 'Work is done.',
            'self' => 'https://example.atlassian.net/rest/api/3/resolution/1',
        ];

        $resolution = Resolution::make($data);

        $this->assertSame($data, $resolution->toArray(), 'Resolution::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $resolution = Resolution::make();

        $this->assertSame('', $resolution->getId(), 'Resolution ID should default to an empty string when not provided');
        $this->assertSame('', $resolution->getName(), 'Resolution name should default to an empty string when not provided');
        $this->assertSame('', $resolution->getDescription(), 'Resolution description should default to an empty string when not provided');
        $this->assertSame('', $resolution->getSelf(), 'Resolution self URL should default to an empty string when not provided');
    }
}
