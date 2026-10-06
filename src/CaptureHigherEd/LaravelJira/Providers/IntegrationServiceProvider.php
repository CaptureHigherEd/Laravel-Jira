<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Providers;

use CaptureHigherEd\LaravelJira\Jira as JiraService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerJiraService();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [JiraService::class];
    }

    /**
     * Register Jira
     *
     * @return void
     */
    public function registerJiraService()
    {
        $config = __DIR__.'/../config/jira.php';
        $this->mergeConfigFrom($config, 'jira');
        $this->publishes([$config => config_path('jira.php')]);

        $this->app->singleton(
            JiraService::class,
            function ($app) {
                if ($token = config('jira.token')) {
                    return JiraService::create($token);
                }

                return null;
            }
        );
    }
}
