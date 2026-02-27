<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Worklogs;
use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
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
        $api = new Worklogs($this->makeConfig($response));

        $result = $api->index('KEY-1');

        $this->assertInstanceOf(ModelsWorklogs::class, $result, 'Worklogs::index() should return a ModelsWorklogs instance');
        $this->assertCount(1, $result->getWorklogs(), 'Worklogs::index() should return exactly 1 worklog from the response');
        $this->assertSame(1, $result->getTotal(), 'Worklogs::index() should hydrate the total count correctly');
    }

    public function test_create(): void
    {
        $worklogData = ['id' => '200', 'self' => '', 'comment' => [], 'started' => '2024-01-01T09:00:00.000+0000', 'timeSpent' => '2h', 'timeSpentSeconds' => 7200, 'issueId' => '10001', 'created' => '', 'updated' => ''];
        $response = $this->mockResponse(201, $worklogData);
        $api = new Worklogs($this->makeConfig($response));

        $result = $api->create('KEY-1', ['timeSpent' => '2h', 'started' => '2024-01-01T09:00:00.000+0000']);

        $this->assertInstanceOf(Worklog::class, $result, 'Worklogs::create() should return a Worklog instance');
        $this->assertSame('200', $result->getId(), 'Worklogs::create() should return the new worklog with the correct ID');
    }

    public function test_update(): void
    {
        $worklogData = ['id' => '200', 'self' => '', 'comment' => [], 'started' => '', 'timeSpent' => '3h', 'timeSpentSeconds' => 10800, 'issueId' => '10001', 'created' => '', 'updated' => ''];
        $response = $this->jsonResponse($worklogData);
        $api = new Worklogs($this->makeConfig($response));

        $result = $api->update('KEY-1', '200', ['timeSpent' => '3h']);

        $this->assertInstanceOf(Worklog::class, $result, 'Worklogs::update() should return a Worklog instance');
        $this->assertSame('3h', $result->getTimeSpent(), 'Worklogs::update() should return the updated worklog with the correct timeSpent');
    }

    public function test_delete(): void
    {
        $response = $this->noContentResponse();
        $api = new Worklogs($this->makeConfig($response));

        $result = $api->delete('KEY-1', '200');

        $this->assertSame([], $result, 'Worklogs::delete() should return an empty array for a successful 204 No Content response');
    }

    public function test_paginate(): void
    {
        $page1 = $this->jsonResponse(['worklogs' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 0]);
        $page2 = $this->jsonResponse(['worklogs' => [], 'total' => 2, 'maxResults' => 1, 'startAt' => 1]);
        $api = new Worklogs($this->makeConfigWithResponses([$page1, $page2]));

        $pages = iterator_to_array($api->paginate('KEY-1'));

        $this->assertCount(2, $pages, 'Worklogs::paginate() should yield one page per HTTP response');
        $this->assertInstanceOf(ModelsWorklogs::class, $pages[0], 'Each yielded value should be a ModelsWorklogs instance');
        $this->assertSame(2, $pages[0]->getTotal(), 'Total should be hydrated from the first page response');
    }

    // ── Validation ────────────────────────────────────────────────────────

    private function makeApi(): Worklogs
    {
        return new Worklogs($this->makeConfig($this->jsonResponse([])));
    }

    public function test_index_throws_on_empty_issue_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->index('');
    }

    public function test_create_throws_on_empty_issue_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->create('');
    }

    public function test_update_throws_on_empty_issue_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->update('', '200');
    }

    public function test_update_throws_on_empty_worklog_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->update('KEY-1', '');
    }

    public function test_delete_throws_on_empty_issue_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->delete('', '200');
    }

    public function test_delete_throws_on_empty_worklog_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->delete('KEY-1', '');
    }

    public function test_paginate_throws_on_empty_issue_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        iterator_to_array($this->makeApi()->paginate(''));
    }
}
