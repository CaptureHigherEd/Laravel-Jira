<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Attachment;
use CaptureHigherEd\LaravelJira\Models\Attachments;
use PHPUnit\Framework\TestCase;

class AttachmentsTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            [
                'id' => '1',
                'filename' => 'a.png',
                'mimeType' => 'image/png',
                'size' => 100,
                'content' => 'https://example.com/a.png',
                'self' => 'https://example.com/attachment/1',
            ],
            [
                'id' => '2',
                'filename' => 'b.pdf',
                'mimeType' => 'application/pdf',
                'size' => 200,
                'content' => 'https://example.com/b.pdf',
                'self' => 'https://example.com/attachment/2',
            ],
        ];

        $attachments = Attachments::make($data);

        $this->assertCount(2, $attachments->getAttachments());
        $this->assertContainsOnlyInstancesOf(Attachment::class, $attachments->getAttachments());
        $this->assertSame($data, $attachments->toArray());
    }

    public function test_make_with_empty_array(): void
    {
        $attachments = Attachments::make([]);

        $this->assertSame([], $attachments->getAttachments());
        $this->assertSame([], $attachments->toArray());
    }
}
