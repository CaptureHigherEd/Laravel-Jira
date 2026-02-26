<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Attachment;
use CaptureHigherEd\LaravelJira\Models\User;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    // ── make & toArray ────────────────────────────────────────────────────

    public function test_make_roundtrip(): void
    {
        $authorData = [
            'accountId' => 'abc123',
            'displayName' => 'Jane Smith',
            'emailAddress' => 'jane@example.com',
            'active' => true,
            'self' => '',
            'accountType' => '',
            'timeZone' => '',
            'locale' => '',
            'avatarUrls' => [],
        ];
        $data = [
            'id' => '12345',
            'filename' => 'screenshot.png',
            'mimeType' => 'image/png',
            'size' => 98765,
            'content' => 'https://example.atlassian.net/content/screenshot.png',
            'self' => 'https://example.atlassian.net/rest/api/3/attachment/12345',
            'author' => $authorData,
            'created' => '2024-01-01T00:00:00.000+0000',
            'thumbnail' => 'https://example.atlassian.net/thumbnail/12345',
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
        $this->assertNull($attachment->getAuthor(), 'Attachment author should default to null when not provided');
        $this->assertSame('', $attachment->getCreated(), 'Attachment created timestamp should default to an empty string when not provided');
        $this->assertSame('', $attachment->getThumbnail(), 'Attachment thumbnail URL should default to an empty string when not provided');
    }

    // ── Type casting ──────────────────────────────────────────────────────

    public function test_size_cast_to_int(): void
    {
        $attachment = Attachment::make(['size' => '12345']);

        $this->assertSame(12345, $attachment->getSize(), 'Attachment size should be cast to integer even when provided as a string');
        $this->assertIsInt($attachment->getSize(), 'Attachment size should be stored and returned as an integer');
    }

    // ── Author hydration ──────────────────────────────────────────────────

    public function test_author_is_hydrated_as_user(): void
    {
        $attachment = Attachment::make([
            'id' => '1',
            'author' => ['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => 'alice@example.com', 'active' => true],
        ]);

        $this->assertInstanceOf(User::class, $attachment->getAuthor(), 'Attachment author should be hydrated as a User instance');
        $this->assertSame('u1', $attachment->getAuthor()?->getKey(), 'Attachment author accountId should be hydrated correctly');
    }

    public function test_make_without_author_returns_null(): void
    {
        $attachment = Attachment::make(['id' => '1']);

        $this->assertNull($attachment->getAuthor(), 'Attachment author should be null when not present in the response data');
    }
}
