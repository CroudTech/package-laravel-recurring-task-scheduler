<?php

namespace CroudTech\RecurringTaskScheduler;

use Illuminate\Support\ServiceProvider;

class RecurringTaskSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();

        $this->app->bind(
            \CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract::class,
            \CroudTech\RecurringTaskScheduler\Library\ScheduleParser::class
        );
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(){}

    /**
     * Load migration files
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }
}