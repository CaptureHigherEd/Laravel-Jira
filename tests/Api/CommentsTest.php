<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Comments;
use CaptureHigherEd\LaravelJira\Models\Comment;
use CaptureHigherEd\LaravelJira\Models\Comments as ModelsComments;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
{
    use MocksHttpResponses;

    public function test_index(): void
    {
        $response = $this->jsonResponse([
            'comments' => [
                ['id' => '1', 'body' => [], 'created' => '', 'updated' => '', 'self' => '', 'jsdPublic' => true, 'visibility' => []],
            ],
            'total' => 1,
            'maxResults' => 50,
            'startAt' => 0,
        ]);
        $client = $this->mockClientExpecting('GET', 'issue/KEY-1/comment', ['query' => []], $response);
        $api = new Comments($client);

        $result = $api->index('KEY-1');

        $this->assertInstanceOf(ModelsComments::class, $result, 'Comments::index() should return a ModelsComments instance');
        $this->assertCount(1, $result->getComments(), 'Comments::index() should return exactly 1 comment from the response');
        $this->assertSame(1, $result->getTotal(), 'Comments::index() should hydrate the total count correctly');
    }

    public function test_show(): void
    {
        $response = $this->jsonResponse(['id' => '42', 'body' => [], 'created' => '', 'updated' => '', 'self' => '', 'jsdPublic' => true, 'visibility' => []]);
        $client = $this->mockClientExpecting('GET', 'issue/KEY-1/comment/42', ['query' => []], $response);
        $api = new Comments($client);

        $result = $api->show('KEY-1', '42');

        $this->assertInstanceOf(Comment::class, $result, 'Comments::show() should return a Comment instance');
        $this->assertSame('42', $result->getId(), 'Comments::show() should return the comment with the correct ID');
    }

    public function test_create(): void
    {
        $response = $this->jsonResponse(['id' => '100', 'body' => ['type' => 'doc'], 'created' => '', 'updated' => '', 'self' => '', 'jsdPublic' => true, 'visibility' => []]);
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/comment', ['json' => ['body' => ['type' => 'doc']]], $response);
        $api = new Comments($client);

        $result = $api->create('KEY-1', ['body' => ['type' => 'doc']]);

        $this->assertInstanceOf(Comment::class, $result, 'Comments::create() should return a Comment instance');
        $this->assertSame('100', $result->getId(), 'Comments::create() should return the new comment with the correct ID');
    }

    public function test_update(): void
    {
        $response = $this->jsonResponse(['id' => '42', 'body' => ['type' => 'doc'], 'created' => '', 'updated' => '', 'self' => '', 'jsdPublic' => true, 'visibility' => []]);
        $client = $this->mockClientExpecting('PUT', 'issue/KEY-1/comment/42', ['json' => ['body' => ['type' => 'doc']]], $response);
        $api = new Comments($client);

        $result = $api->update('KEY-1', '42', ['body' => ['type' => 'doc']]);

        $this->assertInstanceOf(Comment::class, $result, 'Comments::update() should return a Comment instance');
        $this->assertSame('42', $result->getId(), 'Comments::update() should return the updated comment with the correct ID');
    }

    public function test_delete(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1/comment/42', ['query' => []], $response);
        $api = new Comments($client);

        $result = $api->delete('KEY-1', '42');

        $this->assertSame([], $result, 'Comments::delete() should return an empty array for a successful 204 No Content response');
    }

    public function test_paginate(): void
    {
        $page1 = $this->jsonResponse(['comments' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 0]);
        $page2 = $this->jsonResponse(['comments' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 1]);
        $client = $this->mockClientWithResponses([$page1, $page2]);
        $api = new Comments($client);

        $pages = iterator_to_array($api->paginate('KEY-1'));

        $this->assertCount(2, $pages, 'Comments::paginate() should yield one page per HTTP response');
        $this->assertInstanceOf(ModelsComments::class, $pages[0], 'Each yielded value should be a ModelsComments instance');
        $this->assertSame(2, $pages[0]->getTotal(), 'Total should be hydrated from the first page response');
    }
}
