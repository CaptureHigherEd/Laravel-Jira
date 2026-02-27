<?php

namespace CaptureHigherEd\LaravelJira\Tests\Exception;

use CaptureHigherEd\LaravelJira\Exception\HydrationException;
use PHPUnit\Framework\TestCase;

class HydrationExceptionTest extends TestCase
{
    public function test_json_decode_failed_includes_body_preview(): void
    {
        $e = HydrationException::jsonDecodeFailed('not-valid-json');

        $this->assertStringContainsString('not-valid-json', $e->getMessage(), 'jsonDecodeFailed() should include the body in the message');
    }

    public function test_json_decode_failed_truncates_long_body(): void
    {
        $body = str_repeat('x', 200);
        $e = HydrationException::jsonDecodeFailed($body);

        $this->assertStringContainsString('...', $e->getMessage(), 'jsonDecodeFailed() should truncate long bodies with ellipsis');
    }

    public function test_unexpected_content_type_includes_type(): void
    {
        $e = HydrationException::unexpectedContentType('text/html');

        $this->assertStringContainsString('text/html', $e->getMessage(), 'unexpectedContentType() should include the content type in the message');
    }
}
