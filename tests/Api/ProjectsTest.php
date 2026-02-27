<?php

namespace CaptureHigherEd\LaravelJira\Tests\Api;

use CaptureHigherEd\LaravelJira\Api\Projects;
use CaptureHigherEd\LaravelJira\Exception\InvalidArgumentException;
use CaptureHigherEd\LaravelJira\Models\Project;
use CaptureHigherEd\LaravelJira\Models\Projects as ModelsProjects;
use CaptureHigherEd\LaravelJira\Tests\Concerns\MocksHttpResponses;
use PHPUnit\Framework\TestCase;

class ProjectsTest extends TestCase
{
    use MocksHttpResponses;

    public function test_index(): void
    {
        $response = $this->jsonResponse([
            ['id' => '10000', 'key' => 'TEST', 'name' => 'Test Project', 'self' => '', 'projectTypeKey' => 'software', 'simplified' => false, 'avatarUrls' => []],
        ]);
        $client = $this->mockClientExpecting('GET', 'project', ['query' => []], $response);
        $api = new Projects($client);

        $result = $api->index();

        $this->assertInstanceOf(ModelsProjects::class, $result, 'Projects::index() should return a Projects model instance');
        $this->assertCount(1, $result->getProjects(), 'Projects::index() should return exactly 1 project from the response');
        $this->assertSame('TEST', $result->getProjects()[0]->getKey(), 'Projects::index() should hydrate the project key correctly');
    }

    public function test_index_with_params(): void
    {
        $response = $this->jsonResponse([]);
        $client = $this->mockClientExpecting('GET', 'project', ['query' => ['maxResults' => 10]], $response);
        $api = new Projects($client);

        $result = $api->index(['maxResults' => 10]);

        $this->assertInstanceOf(ModelsProjects::class, $result, 'Projects::index() should return a Projects model when called with params');
    }

    public function test_show(): void
    {
        $response = $this->jsonResponse(['id' => '10000', 'key' => 'TEST', 'name' => 'Test Project', 'self' => '', 'projectTypeKey' => 'software', 'simplified' => false, 'avatarUrls' => []]);
        $client = $this->mockClientExpecting('GET', 'project/TEST', ['query' => []], $response);
        $api = new Projects($client);

        $result = $api->show('TEST');

        $this->assertInstanceOf(Project::class, $result, 'Projects::show() should return a Project model instance');
        $this->assertSame('TEST', $result->getKey(), 'Projects::show() should return the project with the correct key');
    }

    // ── Validation ────────────────────────────────────────────────────────

    public function test_show_throws_on_empty_project_key(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $api = new Projects($this->mockClient($this->jsonResponse([])));
        $api->show('');
    }
}
