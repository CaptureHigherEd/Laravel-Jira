<?php

namespace CaptureHigherEd\LaravelJira\Tests;

use CaptureHigherEd\LaravelJira\Jira;
use CaptureHigherEd\LaravelJira\Providers\IntegrationServiceProvider;
use Orchestra\Testbench\TestCase;

class JiraTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [IntegrationServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('jira.token', base64_encode('test@example.com:fake-token'));
        $app['config']->set('jira.domain', 'https://test.atlassian.net');
    }

    public function test_jira_service_can_be_resolved(): void
    {
        $jira = $this->app->make(Jira::class);

        $this->assertInstanceOf(Jira::class, $jira);
    }
}
