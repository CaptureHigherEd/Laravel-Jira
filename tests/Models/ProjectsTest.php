<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Project;
use CaptureHigherEd\LaravelJira\Models\Projects;
use PHPUnit\Framework\TestCase;

class ProjectsTest extends TestCase
{
    public function test_make_with_empty_data(): void
    {
        $projects = Projects::make();

        $this->assertSame([], $projects->getProjects(), 'Projects should default to an empty array when not provided');
    }

    public function test_make_hydrates_projects(): void
    {
        $data = [
            ['id' => '10000', 'key' => 'TEST', 'name' => 'Test Project', 'self' => '', 'projectTypeKey' => 'software', 'simplified' => false, 'avatarUrls' => []],
            ['id' => '10001', 'key' => 'DEMO', 'name' => 'Demo Project', 'self' => '', 'projectTypeKey' => 'business', 'simplified' => true, 'avatarUrls' => []],
        ];

        $projects = Projects::make($data);

        $this->assertCount(2, $projects->getProjects(), 'Projects should hydrate the correct number of items');
        $this->assertInstanceOf(Project::class, $projects->getProjects()[0], 'Each project item should be a Project instance');
        $this->assertSame('TEST', $projects->getProjects()[0]->getKey(), 'First project key should be hydrated correctly');
    }

    public function test_to_array(): void
    {
        $data = [
            ['id' => '10000', 'key' => 'TEST', 'name' => 'Test Project', 'self' => '', 'projectTypeKey' => 'software', 'simplified' => false, 'avatarUrls' => []],
        ];

        $projects = Projects::make($data);

        $this->assertSame($data, $projects->toArray(), 'Projects::toArray() should return the original data array');
    }
}
