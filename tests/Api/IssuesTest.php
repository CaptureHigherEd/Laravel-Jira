<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Issues;
use CaptureHigherEd\LaravelJira\Models\Attachments;
use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\Search;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class IssuesTest extends TestCase
{
    use MocksHttpResponses;

    // ── CRUD ──────────────────────────────────────────────────────────────

    public function test_index(): void
    {
        $response = $this->jsonResponse([
            'issues' => [['id' => '1', 'key' => 'KEY-1', 'fields' => ['summary' => 'Test']]],
        ]);
        $client = $this->mockClientExpecting('GET', 'search', ['query' => ['jql' => 'project=TEST']], $response);
        $api = new Issues($client);

        $result = $api->index(['jql' => 'project=TEST']);

        $this->assertInstanceOf(Search::class, $result, 'Issues::index() should return a Search instance');
        $this->assertCount(1, $result->getIssues(), 'Issues::index() search result should contain exactly 1 issue from the response');
    }

    public function test_show(): void
    {
        $response = $this->jsonResponse(['id' => '10', 'key' => 'KEY-10', 'fields' => ['summary' => 'Issue']]);
        $client = $this->mockClientExpecting('GET', 'issue/KEY-10', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->show('KEY-10');

        $this->assertInstanceOf(Issue::class, $result, 'Issues::show() should return an Issue instance');
        $this->assertSame('KEY-10', $result->getKey(), 'Issues::show() should return the issue with the correct key');
    }

    public function test_create(): void
    {
        $response = $this->mockResponse(201, ['id' => '11', 'key' => 'KEY-11', 'fields' => []]);
        $client = $this->mockClientExpecting('POST', 'issue', ['json' => ['fields' => ['summary' => 'New']]], $response);
        $api = new Issues($client);

        $result = $api->create(['fields' => ['summary' => 'New']]);

        $this->assertInstanceOf(Issue::class, $result, 'Issues::create() should return an Issue instance');
        $this->assertSame('KEY-11', $result->getKey(), 'Issues::create() should return the newly created issue with the correct key');
    }

    // ── Attachments & comments ────────────────────────────────────────────

    public function test_attach(): void
    {
        $response = $this->jsonResponse([
            ['id' => '1', 'filename' => 'test.txt', 'mimeType' => 'text/plain', 'size' => 10, 'content' => '', 'self' => ''],
        ]);
        $multipart = [['name' => 'file', 'contents' => 'data', 'filename' => 'test.txt']];
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/attachments', [
            'multipart' => $multipart,
            'headers' => ['Accept' => 'application/json', 'X-Atlassian-Token' => 'no-check'],
        ], $response);
        $api = new Issues($client);

        $result = $api->attach('KEY-1', $multipart);

        $this->assertInstanceOf(Attachments::class, $result, 'Issues::attach() should return an Attachments instance');
        $this->assertCount(1, $result->getAttachments(), 'Issues::attach() should return the correct number of attachments from the response');
    }

    public function test_comment(): void
    {
        $response = $this->jsonResponse([
            'id' => '200',
            'body' => [],
            'created' => '2024-01-01T00:00:00.000+0000',
            'updated' => '2024-01-01T00:00:00.000+0000',
            'self' => 'https://example.com/comment/200',
        ]);
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/comment', ['json' => ['body' => []]], $response);
        $api = new Issues($client);

        $result = $api->comment('KEY-1', ['body' => []]);

        $this->assertInstanceOf(Comment::class, $result, 'Issues::comment() should return a Comment instance');
        $this->assertSame('200', $result->getId(), 'Issues::comment() should return the comment with the correct ID from the response');
    }

    public function test_update(): void
    {
        $response = $this->mockResponse(200, ['id' => '10', 'key' => 'KEY-10', 'fields' => ['summary' => 'Updated']]);
        $client = $this->mockClientExpecting('PUT', 'issue/KEY-10', ['json' => ['fields' => ['summary' => 'Updated']]], $response);
        $api = new Issues($client);

        $result = $api->update('KEY-10', ['fields' => ['summary' => 'Updated']]);

        $this->assertInstanceOf(Issue::class, $result, 'Issues::update() should return an Issue instance');
    }

    // ── Metadata ──────────────────────────────────────────────────────────

    public function test_get_create_meta(): void
    {
        $meta = ['projects' => [['key' => 'TEST', 'issuetypes' => []]]];
        $response = $this->jsonResponse($meta);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->getCreateMeta();

        $this->assertSame($meta, $result, 'Issues::getCreateMeta() should return the raw create metadata array from the API response');
    }

    public function test_delete(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1', [], $response);
        $api = new Issues($client);

        $result = $api->delete('KEY-1');

        $this->assertSame([], $result, 'Issues::delete() should return an empty array for a successful 204 No Content response');
    }
}
