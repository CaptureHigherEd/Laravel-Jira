<?php

namespace CaptureHigherEd\LaravelJira\Tests\Http;

use CaptureHigherEd\LaravelJira\Http\RequestBuilder;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class RequestBuilderTest extends TestCase
{
    private RequestBuilder $builder;

    protected function setUp(): void
    {
        $factory = new HttpFactory;
        $this->builder = new RequestBuilder($factory, $factory);
    }

    // ── create (GET / DELETE) ─────────────────────────────────────────────

    public function test_create_returns_request_with_correct_method(): void
    {
        $request = $this->builder->create('GET', 'https://example.com/search');

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame('GET', $request->getMethod());
    }

    public function test_create_returns_request_with_correct_uri(): void
    {
        $request = $this->builder->create('DELETE', 'https://example.com/issue/KEY-1');

        $this->assertSame('https://example.com/issue/KEY-1', (string) $request->getUri());
    }

    public function test_create_has_no_body(): void
    {
        $request = $this->builder->create('GET', 'https://example.com/search');

        $this->assertSame('', (string) $request->getBody());
    }

    // ── createWithJson (POST / PUT) ───────────────────────────────────────

    public function test_create_with_json_sets_content_type(): void
    {
        $request = $this->builder->createWithJson('POST', 'https://example.com/issue', ['summary' => 'Test']);

        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
    }

    public function test_create_with_json_encodes_body(): void
    {
        $data = ['fields' => ['summary' => 'Test issue']];
        $request = $this->builder->createWithJson('POST', 'https://example.com/issue', $data);

        $this->assertSame(json_encode($data), (string) $request->getBody());
    }

    public function test_create_with_json_sets_method(): void
    {
        $request = $this->builder->createWithJson('PUT', 'https://example.com/issue/KEY-1', []);

        $this->assertSame('PUT', $request->getMethod());
    }

    public function test_create_with_json_handles_empty_array(): void
    {
        $request = $this->builder->createWithJson('POST', 'https://example.com/issue', []);

        $this->assertSame('[]', (string) $request->getBody());
    }

    // ── createWithRawBody ─────────────────────────────────────────────────

    public function test_create_with_raw_body_sets_body(): void
    {
        $request = $this->builder->createWithRawBody('POST', 'https://example.com/issue/KEY-1/watchers', '"u1"', 'application/json');

        $this->assertSame('"u1"', (string) $request->getBody());
    }

    public function test_create_with_raw_body_sets_content_type(): void
    {
        $request = $this->builder->createWithRawBody('POST', 'https://example.com/issue/KEY-1/watchers', '"u1"', 'application/json');

        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
    }

    public function test_create_with_raw_body_sets_method(): void
    {
        $request = $this->builder->createWithRawBody('POST', 'https://example.com/test', 'data', 'text/plain');

        $this->assertSame('POST', $request->getMethod());
    }

    // ── createWithMultipart ───────────────────────────────────────────────

    public function test_create_with_multipart_sets_method(): void
    {
        $parts = [['name' => 'file', 'contents' => 'file-data', 'filename' => 'test.txt']];
        $request = $this->builder->createWithMultipart('POST', 'https://example.com/issue/KEY-1/attachments', $parts);

        $this->assertSame('POST', $request->getMethod());
    }

    public function test_create_with_multipart_sets_multipart_content_type(): void
    {
        $parts = [['name' => 'file', 'contents' => 'file-data', 'filename' => 'test.txt']];
        $request = $this->builder->createWithMultipart('POST', 'https://example.com/issue/KEY-1/attachments', $parts);

        $this->assertStringStartsWith('multipart/form-data', $request->getHeaderLine('Content-Type'));
    }

    public function test_create_with_multipart_includes_boundary(): void
    {
        $parts = [['name' => 'file', 'contents' => 'file-data']];
        $request = $this->builder->createWithMultipart('POST', 'https://example.com/attachments', $parts);

        $this->assertStringContainsString('boundary=', $request->getHeaderLine('Content-Type'));
    }

    public function test_create_with_multipart_body_contains_file_content(): void
    {
        $parts = [['name' => 'file', 'contents' => 'hello-world', 'filename' => 'hello.txt']];
        $request = $this->builder->createWithMultipart('POST', 'https://example.com/attachments', $parts);

        $this->assertStringContainsString('hello-world', (string) $request->getBody());
    }
}
