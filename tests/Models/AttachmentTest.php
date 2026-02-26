<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
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

        $this->assertSame($data, $attachment->toArray());
    }

    public function test_make_with_empty_data(): void
    {
        $attachment = Attachment::make();

        $this->assertSame('', $attachment->getId());
        $this->assertSame('', $attachment->getFilename());
        $this->assertSame('', $attachment->getMimeType());
        $this->assertSame(0, $attachment->getSize());
        $this->assertSame('', $attachment->getContent());
        $this->assertSame('', $attachment->getSelf());
    }

    public function test_size_cast_to_int(): void
    {
        $attachment = Attachment::make(['size' => '12345']);

        $this->assertSame(12345, $attachment->getSize());
        $this->assertIsInt($attachment->getSize());
    }
}
