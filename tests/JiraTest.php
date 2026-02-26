<?php

namespace CaptureHigherEd\LaravelJira\Tests;

use CaptureHigherEd\LaravelJira\Api\Fields;
use CaptureHigherEd\LaravelJira\Api\Issues;
use CaptureHigherEd\LaravelJira\Api\Users;
use CaptureHigherEd\LaravelJira\Jira;
use CaptureHigherEd\LaravelJira\Tests\Concerns\UsesTestbench;
use Orchestra\Testbench\TestCase;

class JiraTest extends TestCase
{
    use UsesTestbench;

    // ── Service resolution ────────────────────────────────────────────────

    public function test_jira_service_can_be_resolved(): void
    {
        $jira = $this->app->make(Jira::class);

        $this->assertInstanceOf(Jira::class, $jira, 'Jira service should resolve to a Jira instance when credentials are present');
    }

    public function test_jira_service_returns_null_when_token_missing(): void
    {
        $this->app['config']->set('jira.token', null);

        $jira = $this->app->make(Jira::class);

        $this->assertNull($jira, 'Jira service should return null when the API token is not configured');
    }

    // ── API accessors ─────────────────────────────────────────────────────

    public function test_jira_exposes_issues_api(): void
    {
        $jira = $this->app->make(Jira::class);

        $this->assertInstanceOf(Issues::class, $jira->issues(), 'Jira::issues() should return an Issues API instance');
    }

    public function test_jira_exposes_fields_api(): void
    {
        $jira = $this->app->make(Jira::class);

        $this->assertInstanceOf(Fields::class, $jira->fields(), 'Jira::fields() should return a Fields API instance');
    }

    public function test_jira_exposes_users_api(): void
    {
        $jira = $this->app->make(Jira::class);

        $this->assertInstanceOf(Users::class, $jira->users(), 'Jira::users() should return a Users API instance');
    }
}
