<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Worklogs;
use CaptureHigherEd\LaravelJira\Models\Worklog;
use CaptureHigherEd\LaravelJira\Models\Worklogs as ModelsWorklogs;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class WorklogsTest extends TestCase
{
    use MocksHttpResponses;

    public function test_index(): void
    {
        $response = $this->jsonResponse([
            'worklogs' => [
                ['id' => '1', 'self' => '', 'comment' => [], 'started' => '2024-01-01T09:00:00.000+0000', 'timeSpent' => '1h', 'timeSpentSeconds' => 3600, 'issueId' => '10001', 'created' => '', 'updated' => ''],
            ],
            'total' => 1,
            'maxResults' => 20,
            'startAt' => 0,
        ]);
        $client = $this->mockClientExpecting('GET', 'issue/KEY-1/worklog', ['query' => []], $response);
        $api = new Worklogs($client);

        $result = $api->index('KEY-1');

        $this->assertInstanceOf(ModelsWorklogs::class, $result, 'Worklogs::index() should return a ModelsWorklogs instance');
        $this->assertCount(1, $result->getWorklogs(), 'Worklogs::index() should return exactly 1 worklog from the response');
        $this->assertSame(1, $result->getTotal(), 'Worklogs::index() should hydrate the total count correctly');
    }

    public function test_create(): void
    {
        $worklogData = ['id' => '200', 'self' => '', 'comment' => [], 'started' => '2024-01-01T09:00:00.000+0000', 'timeSpent' => '2h', 'timeSpentSeconds' => 7200, 'issueId' => '10001', 'created' => '', 'updated' => ''];
        $response = $this->mockResponse(201, $worklogData);
        $client = $this->mockClientExpecting('POST', 'issue/KEY-1/worklog', ['json' => ['timeSpent' => '2h', 'started' => '2024-01-01T09:00:00.000+0000']], $response);
        $api = new Worklogs($client);

        $result = $api->create('KEY-1', ['timeSpent' => '2h', 'started' => '2024-01-01T09:00:00.000+0000']);

        $this->assertInstanceOf(Worklog::class, $result, 'Worklogs::create() should return a Worklog instance');
        $this->assertSame('200', $result->getId(), 'Worklogs::create() should return the new worklog with the correct ID');
    }

    public function test_update(): void
    {
        $worklogData = ['id' => '200', 'self' => '', 'comment' => [], 'started' => '', 'timeSpent' => '3h', 'timeSpentSeconds' => 10800, 'issueId' => '10001', 'created' => '', 'updated' => ''];
        $response = $this->jsonResponse($worklogData);
        $client = $this->mockClientExpecting('PUT', 'issue/KEY-1/worklog/200', ['json' => ['timeSpent' => '3h']], $response);
        $api = new Worklogs($client);

        $result = $api->update('KEY-1', '200', ['timeSpent' => '3h']);

        $this->assertInstanceOf(Worklog::class, $result, 'Worklogs::update() should return a Worklog instance');
        $this->assertSame('3h', $result->getTimeSpent(), 'Worklogs::update() should return the updated worklog with the correct timeSpent');
    }

    public function test_delete(): void
    {
        $response = $this->noContentResponse();
        $client = $this->mockClientExpecting('DELETE', 'issue/KEY-1/worklog/200', ['query' => []], $response);
        $api = new Worklogs($client);

        $result = $api->delete('KEY-1', '200');

        $this->assertSame([], $result, 'Worklogs::delete() should return an empty array for a successful 204 No Content response');
    }

    public function test_paginate(): void
    {
        $page1 = $this->jsonResponse(['worklogs' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 0]);
        $page2 = $this->jsonResponse(['worklogs' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 1]);
        $client = $this->mockClientWithResponses([$page1, $page2]);
        $api = new Worklogs($client);

        $pages = iterator_to_array($api->paginate('KEY-1'));

        $this->assertCount(2, $pages, 'Worklogs::paginate() should yield one page per HTTP response');
        $this->assertInstanceOf(ModelsWorklogs::class, $pages[0], 'Each yielded value should be a ModelsWorklogs instance');
        $this->assertSame(2, $pages[0]->getTotal(), 'Total should be hydrated from the first page response');
    }
}
