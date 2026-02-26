<?php

namespace CaptureHigherEd\LaravelJira\Tests\Concerns;

use CaptureHigherEd\LaravelJira\Providers\IntegrationServiceProvider;

trait UsesTestbench
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
}
