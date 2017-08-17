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

        $this->app->bind(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract::class, function ($app, $args) {
            if (!isset($args['definition']['type'])) {
                throw new \CroudTech\RecurringTaskScheduler\Exceptions\InvalidArgument(sprintf('No definition type was provided'));
            }

            $classname = sprintf('\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\%s', ucfirst(camel_case($args['definition']['type'])));
            if (!class_exists($classname)) {
                throw new \CroudTech\RecurringTaskScheduler\Exceptions\InvalidArgument(sprintf('There is no ScheduleParserContract implementation that matches the definition type "%s"', $args['definition']['type']));
            }

            return new $classname($args['definition']);
        });

        $this->app->singleton(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class, function ($app) {
            return new \CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory($app);
        });

        $this->app->register(\CroudTech\Repositories\Providers\RepositoryServiceProvider::class);
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