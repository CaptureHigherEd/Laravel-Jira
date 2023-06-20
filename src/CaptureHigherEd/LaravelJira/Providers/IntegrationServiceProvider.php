<?php

namespace CaptureHigherEd\LaravelJira\Providers;

use App\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use CaptureHigherEd\LaravelJira\Jira as JiraService;
use Illuminate\Contracts\Support\DeferrableProvider;

class IntegrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {
    }

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
     * @return array
     */
    public function provides()
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
        $config = __DIR__ . '/../config/jira.php';
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
