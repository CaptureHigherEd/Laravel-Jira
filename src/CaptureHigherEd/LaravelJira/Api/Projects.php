<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Api;

use CaptureHigherEd\LaravelJira\Assert;
use CaptureHigherEd\LaravelJira\Models\Project;
use CaptureHigherEd\LaravelJira\Models\Projects as ModelsProjects;

/**
 * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-projects/#api-group-projects
 */
class Projects extends HttpApi
{
    /**
     * Get all projects
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-projects/#api-rest-api-3-project-get
     *
     * @param  array<string, mixed>  $params
     */
    public function index(array $params = []): ModelsProjects
    {
        $response = $this->httpGet('project', $params);

        return $this->hydrateResponse($response, ModelsProjects::class);
    }

    /**
     * Get a project by key or ID
     *
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/api-group-projects/#api-rest-api-3-project-projectidorkey-get
     *
     * @param  array<string, mixed>  $params
     */
    public function show(string $projectKey, array $params = []): Project
    {
        Assert::stringNotEmpty($projectKey, 'Project key must not be empty.');
        $response = $this->httpGet(sprintf('project/%s', $projectKey), $params);

        return $this->hydrateResponse($response, Project::class);
    }
}
