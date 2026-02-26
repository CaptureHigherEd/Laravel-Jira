<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    // ── make & toArray ────────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '12345',
            'filename' => 'screenshot.png',
            'mimeType' => 'image/png',
            'size' => 98765,
            'content' => 'https://example.atlassian.net/content/screenshot.png',
            'self' => 'https://example.atlassian.net/rest/api/3/attachment/12345',
        ];

        $attachment = Attachment::make($data);

        $this->assertSame($data, $attachment->toArray(), 'Attachment::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $attachment = Attachment::make();

        $this->assertSame('', $attachment->getId(), 'Attachment ID should default to an empty string when not provided');
        $this->assertSame('', $attachment->getFilename(), 'Attachment filename should default to an empty string when not provided');
        $this->assertSame('', $attachment->getMimeType(), 'Attachment MIME type should default to an empty string when not provided');
        $this->assertSame(0, $attachment->getSize(), 'Attachment size should default to 0 when not provided');
        $this->assertSame('', $attachment->getContent(), 'Attachment content URL should default to an empty string when not provided');
        $this->assertSame('', $attachment->getSelf(), 'Attachment self URL should default to an empty string when not provided');
    }

    // ── Type casting ──────────────────────────────────────────────────────

    public function test_size_cast_to_int(): void
    {
        $attachment = Attachment::make(['size' => '12345']);

        $this->assertSame(12345, $attachment->getSize(), 'Attachment size should be cast to integer even when provided as a string');
        $this->assertIsInt($attachment->getSize(), 'Attachment size should be stored and returned as an integer');
    }
}
