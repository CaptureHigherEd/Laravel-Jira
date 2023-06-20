<?php

namespace tests;

use Tests\TestCase;
use CaptureHigherEd\LaravelJira\Jira;

/**
 * @group Integrations
 * @group skip
 */
class JiraTest extends TestCase
{
    private $jiraService;

    protected function setUp(): void
    {
        $this->jiraService = app(Jira::class);

        $this->assertNotNull($this->jiraService);

        parent::setUp();
    }

    public function test_get_issues()
    {
        $issues = $this->jiraService->issues()->index();
        $this->assertNotNull($issues);

        $issueKey = $issues->getIssues()[0]->getKey();
        $issue = $this->jiraService->issues()->show($issueKey);
        $this->assertNotNull($issue);
    }
}
