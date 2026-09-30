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
                'author' => null,
                'created' => '2024-01-01T00:00:00.000+0000',
                'thumbnail' => '',
            ],
            [
                'id' => '2',
                'filename' => 'b.pdf',
                'mimeType' => 'application/pdf',
                'size' => 200,
                'content' => 'https://example.com/b.pdf',
                'self' => 'https://example.com/attachment/2',
                'author' => null,
                'created' => '2024-01-02T00:00:00.000+0000',
                'thumbnail' => '',
            ],
        ];

        $attachments = Attachments::make($data);

        $this->assertCount(2, $attachments->getAttachments(), 'Attachments collection should contain exactly 2 items matching the input array');
        $this->assertContainsOnlyInstancesOf(Attachment::class, $attachments->getAttachments(), 'All items in the Attachments collection should be hydrated as Attachment instances');
        $this->assertSame($data, $attachments->toArray(), 'Attachments::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_array(): void
    {
        $attachments = Attachments::make([]);

        $this->assertSame([], $attachments->getAttachments(), 'Attachments collection should be an empty array when constructed with no data');
        $this->assertSame([], $attachments->toArray(), 'Attachments::toArray() should return an empty array when no attachments are present');
    }
}
