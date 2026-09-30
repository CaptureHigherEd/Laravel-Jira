<?php

namespace CaptureHigherEd\LaravelJira\Tests\Models;

use CaptureHigherEd\LaravelJira\Models\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function test_make_roundtrip(): void
    {
        $data = [
            'id' => '10000',
            'key' => 'TEST',
            'name' => 'Test Project',
            'self' => 'https://example.atlassian.net/rest/api/3/project/10000',
            'projectTypeKey' => 'software',
            'simplified' => false,
            'avatarUrls' => ['16x16' => 'https://example.com/16.png', '48x48' => 'https://example.com/48.png'],
        ];

        $project = Project::make($data);

        $this->assertSame($data, $project->toArray(), 'Project::toArray() should return the same data that was passed to make()');
    }

    public function test_make_with_empty_data(): void
    {
        $project = Project::make();

        $this->assertSame('', $project->getId(), 'Project ID should default to an empty string when not provided');
        $this->assertSame('', $project->getKey(), 'Project key should default to an empty string when not provided');
        $this->assertSame('', $project->getName(), 'Project name should default to an empty string when not provided');
        $this->assertSame('', $project->getSelf(), 'Project self URL should default to an empty string when not provided');
        $this->assertSame('', $project->getProjectTypeKey(), 'Project projectTypeKey should default to an empty string when not provided');
        $this->assertFalse($project->getSimplified(), 'Project simplified should default to false when not provided');
        $this->assertSame([], $project->getAvatarUrls(), 'Project avatarUrls should default to an empty array when not provided');
    }
}
