<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Attachments;
use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
use CaptureHigherEd\LaravelJira\Models\Attachment;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class AttachmentsTest extends TestCase
{
    use MocksHttpResponses;

    public function test_show(): void
    {
        $response = $this->jsonResponse([
            'id' => '10001',
            'filename' => 'screenshot.png',
            'mimeType' => 'image/png',
            'size' => 4096,
            'content' => 'https://example.atlassian.net/secure/attachment/10001/screenshot.png',
            'self' => 'https://example.atlassian.net/rest/api/3/attachment/10001',
            'created' => '2024-01-01T09:00:00.000+0000',
            'thumbnail' => '',
        ]);
        $api = new Attachments($this->makeConfig($response));

        $result = $api->show('10001');

        $this->assertInstanceOf(Attachment::class, $result, 'Attachments::show() should return an Attachment instance');
        $this->assertSame('10001', $result->getId(), 'Attachments::show() should return the attachment with the correct ID');
        $this->assertSame('screenshot.png', $result->getFilename(), 'Attachments::show() should hydrate the filename correctly');
    }

    public function test_delete(): void
    {
        $response = $this->noContentResponse();
        $api = new Attachments($this->makeConfig($response));

        $result = $api->delete('10001');

        $this->assertSame([], $result, 'Attachments::delete() should return an empty array for a successful 204 No Content response');
    }

    public function test_get_meta(): void
    {
        $meta = ['enabled' => true, 'uploadLimit' => 10485760];
        $response = $this->jsonResponse($meta);
        $api = new Attachments($this->makeConfig($response));

        $result = $api->getMeta();

        $this->assertSame($meta, $result, 'Attachments::getMeta() should return the raw metadata array from the API response');
    }

    // ── Validation ────────────────────────────────────────────────────────

    private function makeApi(): Attachments
    {
        return new Attachments($this->makeConfig($this->jsonResponse([])));
    }

    public function test_show_throws_on_empty_attachment_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->show('');
    }

    public function test_delete_throws_on_empty_attachment_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->delete('');
    }
}
