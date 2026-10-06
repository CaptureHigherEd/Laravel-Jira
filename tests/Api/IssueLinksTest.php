<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\IssueLinks;
use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
use CaptureHigherEd\LaravelJira\Models\IssueLink;
use CaptureHigherEd\LaravelJira\Models\IssueLinkTypes;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class IssueLinksTest extends TestCase
{
    use MocksHttpResponses;

    public function test_create(): void
    {
        $response = $this->mockResponse(201, null);
        $params = ['type' => ['name' => 'Blocks'], 'inwardIssue' => ['key' => 'KEY-1'], 'outwardIssue' => ['key' => 'KEY-2']];
        $api = new IssueLinks($this->makeConfig($response));

        $result = $api->create($params);

        $this->assertSame([], $result, 'IssueLinks::create() should return an empty array for a 201 response with no body');
    }

    public function test_show(): void
    {
        $response = $this->jsonResponse([
            'id' => '10000',
            'self' => 'https://example.atlassian.net/rest/api/3/issueLink/10000',
            'type' => ['id' => '1', 'name' => 'Blocks', 'inward' => 'is blocked by', 'outward' => 'blocks'],
            'inwardIssue' => ['id' => '10001', 'key' => 'KEY-1'],
            'outwardIssue' => ['id' => '10002', 'key' => 'KEY-2'],
        ]);
        $api = new IssueLinks($this->makeConfig($response));

        $result = $api->show('10000');

        $this->assertInstanceOf(IssueLink::class, $result, 'IssueLinks::show() should return an IssueLink instance');
        $this->assertSame('10000', $result->getId(), 'IssueLinks::show() should return the link with the correct ID');
    }

    public function test_delete(): void
    {
        $response = $this->noContentResponse();
        $api = new IssueLinks($this->makeConfig($response));

        $result = $api->delete('10000');

        $this->assertSame([], $result, 'IssueLinks::delete() should return an empty array for a successful 204 No Content response');
    }

    public function test_get_types(): void
    {
        $response = $this->jsonResponse([
            'issueLinkTypes' => [
                ['id' => '1', 'name' => 'Blocks', 'inward' => 'is blocked by', 'outward' => 'blocks', 'self' => ''],
            ],
        ]);
        $api = new IssueLinks($this->makeConfig($response));

        $result = $api->getTypes();

        $this->assertInstanceOf(IssueLinkTypes::class, $result, 'IssueLinks::getTypes() should return an IssueLinkTypes instance');
        $this->assertCount(1, $result->getTypes(), 'IssueLinks::getTypes() should return the correct number of link types');
        $this->assertSame('Blocks', $result->getTypes()[0]->getName(), 'IssueLinks::getTypes() should hydrate the type name correctly');
    }

    // ── Validation ────────────────────────────────────────────────────────

    private function makeApi(): IssueLinks
    {
        return new IssueLinks($this->makeConfig($this->jsonResponse([])));
    }

    public function test_show_throws_on_empty_link_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->show('');
    }

    public function test_delete_throws_on_empty_link_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->makeApi()->delete('');
    }
}
