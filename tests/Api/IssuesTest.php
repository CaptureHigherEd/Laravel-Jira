<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Issues;
use CaptureHigherEd\LaravelJira\Models\Attachments;
use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\FieldMetas;
use CaptureHigherEd\LaravelJira\Models\Issue;
use CaptureHigherEd\LaravelJira\Models\IssueTypes;
use CaptureHigherEd\LaravelJira\Models\Search;
use CaptureHigherEd\LaravelJira\Models\Transitions;
use CaptureHigherEd\LaravelJira\Models\Watchers;
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
        $client = $this->mockClientExpecting('GET', 'search/jql', ['query' => ['jql' => 'project=TEST']], $response);
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
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('PUT', 'issue/KEY-10', ['json' => ['fields' => ['summary' => 'Updated']]], $response);
        $api = new Issues($client);

        $result = $api->update('KEY-10', ['fields' => ['summary' => 'Updated']]);

        $this->assertSame([], $result, 'Issues::update() should return an empty array for a successful 204 No Content response');
    }

    // ── Metadata ──────────────────────────────────────────────────────────

    public function test_get_create_meta_issue_types(): void
    {
        $response = $this->jsonResponse(['issueTypes' => [['id' => '10001', 'name' => 'Bug', 'description' => '', 'subtask' => false, 'iconUrl' => '', 'self' => '']]]);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta/TEST/issuetypes', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->getCreateMetaIssueTypes('TEST');

        $this->assertInstanceOf(IssueTypes::class, $result, 'Issues::getCreateMetaIssueTypes() should return an IssueTypes instance');
        $this->assertCount(1, $result->getIssueTypes(), 'Issues::getCreateMetaIssueTypes() should return exactly 1 issue type from the response');
        $this->assertSame('10001', $result->getIssueTypes()[0]->getId(), 'Issues::getCreateMetaIssueTypes() should hydrate the issue type id correctly');
    }

    public function test_get_create_meta_fields(): void
    {
        $response = $this->jsonResponse(['fields' => [['fieldId' => 'summary', 'name' => 'Summary', 'required' => true, 'schema' => [], 'operations' => ['set'], 'allowedValues' => []]]]);
        $client = $this->mockClientExpecting('GET', 'issue/createmeta/TEST/issuetypes/10001', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->getCreateMetaFields('TEST', '10001');

        $this->assertInstanceOf(FieldMetas::class, $result, 'Issues::getCreateMetaFields() should return a FieldMetas instance');
        $this->assertCount(1, $result->getFields(), 'Issues::getCreateMetaFields() should return exactly 1 field from the response');
        $this->assertSame('summary', $result->getFields()[0]->getFieldId(), 'Issues::getCreateMetaFields() should hydrate the field id correctly');
    }

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
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->delete('KEY-1');

        $this->assertSame([], $result, 'Issues::delete() should return an empty array for a successful 204 No Content response');
    }

    // ── Transitions & assignment ───────────────────────────────────────────

    public function test_get_transitions(): void
    {
        $response = $this->jsonResponse([
            'transitions' => [
                ['id' => '1', 'name' => 'To Do', 'hasScreen' => false, 'isGlobal' => false, 'isInitial' => true, 'isConditional' => false],
                ['id' => '2', 'name' => 'In Progress', 'hasScreen' => false, 'isGlobal' => true, 'isInitial' => false, 'isConditional' => false],
            ],
        ]);
        $client = $this->mockClientExpecting('GET', 'issue/KEY-1/transitions', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->getTransitions('KEY-1');

        $this->assertInstanceOf(Transitions::class, $result, 'Issues::getTransitions() should return a Transitions instance');
        $this->assertCount(2, $result->getTransitions(), 'Issues::getTransitions() should return the correct number of transitions');
        $this->assertSame('1', $result->getTransitions()[0]->getId(), 'Issues::getTransitions() should hydrate the transition ID correctly');
    }

    public function test_transition(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/transitions', ['json' => ['transition' => ['id' => '5']]], $response);
        $api = new Issues($client);

        $result = $api->transition('KEY-1', ['transition' => ['id' => '5']]);

        $this->assertSame([], $result, 'Issues::transition() should return an empty array for a successful 204 No Content response');
    }

    public function test_assign(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('PUT', 'issue/KEY-1/assignee', ['json' => ['accountId' => 'u1']], $response);
        $api = new Issues($client);

        $result = $api->assign('KEY-1', 'u1');

        $this->assertSame([], $result, 'Issues::assign() should return an empty array for a successful 204 No Content response');
    }

    // ── Watchers ──────────────────────────────────────────────────────────

    public function test_get_watchers(): void
    {
        $response = $this->jsonResponse([
            'self' => 'https://example.atlassian.net/rest/api/3/issue/KEY-1/watchers',
            'isWatching' => true,
            'watchCount' => 1,
            'watchers' => [['accountId' => 'u1', 'displayName' => 'Alice', 'emailAddress' => '', 'active' => true]],
        ]);
        $client = $this->mockClientExpecting('GET', 'issue/KEY-1/watchers', ['query' => []], $response);
        $api = new Issues($client);

        $result = $api->getWatchers('KEY-1');

        $this->assertInstanceOf(Watchers::class, $result, 'Issues::getWatchers() should return a Watchers instance');
        $this->assertSame(1, $result->getWatchCount(), 'Issues::getWatchers() should hydrate the watch count correctly');
        $this->assertCount(1, $result->getWatchers(), 'Issues::getWatchers() should hydrate the watchers list correctly');
    }

    public function test_add_watcher(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/watchers', [
            'body' => '"u1"',
            'headers' => ['Content-Type' => 'application/json'],
        ], $response);
        $api = new Issues($client);

        $result = $api->addWatcher('KEY-1', 'u1');

        $this->assertSame([], $result, 'Issues::addWatcher() should return an empty array for a successful 204 No Content response');
    }

    public function test_remove_watcher(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1/watchers', ['query' => ['accountId' => 'u1']], $response);
        $api = new Issues($client);

        $result = $api->removeWatcher('KEY-1', 'u1');

        $this->assertSame([], $result, 'Issues::removeWatcher() should return an empty array for a successful 204 No Content response');
    }

    // ── pagination ────────────────────────────────────────────────────────

    public function test_paginate(): void
    {
        $page1 = $this->jsonResponse(['issues' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 0]);
        $page2 = $this->jsonResponse(['issues' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 1]);
        $client = $this->mockClientWithResponses([$page1, $page2]);
        $api = new Issues($client);

        $pages = iterator_to_array($api->paginate());

        $this->assertCount(2, $pages, 'Issues::paginate() should yield one Search per page');
        $this->assertInstanceOf(Search::class, $pages[0], 'Each yielded value should be a Search instance');
        $this->assertSame(2, $pages[0]->getTotal(), 'Total should be hydrated from the first page');
    }

    public function test_paginate_create_meta_issue_types(): void
    {
        $response = $this->jsonResponse(['issueTypes' => [], 'total' => 0, 'maxResults' => 50, 'startAt' => 0]);
        $client = $this->mockClientWithResponses([$response]);
        $api = new Issues($client);

        $pages = iterator_to_array($api->paginateCreateMetaIssueTypes('TEST'));

        $this->assertCount(1, $pages, 'paginateCreateMetaIssueTypes() should yield one page');
        $this->assertInstanceOf(IssueTypes::class, $pages[0], 'Each yielded value should be an IssueTypes instance');
    }

    public function test_paginate_create_meta_fields(): void
    {
        $response = $this->jsonResponse(['fields' => [], 'total' => 0, 'maxResults' => 50, 'startAt' => 0]);
        $client = $this->mockClientWithResponses([$response]);
        $api = new Issues($client);

        $pages = iterator_to_array($api->paginateCreateMetaFields('TEST', '10001'));

        $this->assertCount(1, $pages, 'paginateCreateMetaFields() should yield one page');
        $this->assertInstanceOf(FieldMetas::class, $pages[0], 'Each yielded value should be a FieldMetas instance');
    }
}
