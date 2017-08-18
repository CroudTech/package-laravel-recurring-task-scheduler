<?php

namespace CroudTech\RecurringTaskScheduler;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Exceptions\InvalidArgument;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory as ScheduleParserFactory;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepository;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepository;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleEventTransformer;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleTransformer;
use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\Repositories\Contracts\TransformerContract;
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
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ScheduleParserContract::class, function ($app, $args) {
            if (!isset($args['definition']['type'])) {
                throw new InvalidArgument(sprintf('No definition type was provided'));
            }

            $classname = sprintf('\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\%s', ucfirst(camel_case($args['definition']['type'])));
            if (!class_exists($classname)) {
                throw new InvalidArgument(sprintf('There is no ScheduleParserContract implementation that matches the definition type "%s"', $args['definition']['type']));
            }

            return new $classname($args['definition']);
        });

        $this->app->singleton(ScheduleParserFactory::class, function ($app) {
            return new ScheduleParserFactory($app);
        });

        // Repository bindings
        $this->app->bind(ScheduleEventRepositoryContract::class, ScheduleEventRepository::class);
        $this->app->bind(ScheduleRepositoryContract::class, ScheduleRepository::class);

        // Controller repository bindings
        $this->app->when(ScheduleEventController::class)
            ->needs(RepositoryContract::class)
            ->give(ScheduleEventRepositoryContract::class);

        $this->app->when(ScheduleController::class)
            ->needs(RepositoryContract::class)
            ->give(ScheduleRepositoryContract::class);

        // Transformers
        $this->app->when(ScheduleEventRepository::class)
            ->needs(TransformerContract::class)
            ->give(ScheduleEventTransformer::class);

        $this->app->when(ScheduleRepository::class)
            ->needs(TransformerContract::class)
            ->give(ScheduleTransformer::class);
    }

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