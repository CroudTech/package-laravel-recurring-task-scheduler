<?php

namespace CroudTech\RecurringTaskScheduler;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Exceptions\InvalidArgument;
use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleController;
use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleEventsNestedController;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory as ScheduleParserFactory;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\RecurringTaskScheduler\Observers\ScheduleEventObserver;
use CroudTech\RecurringTaskScheduler\Observers\ScheduleObserver;
use CroudTech\RecurringTaskScheduler\Repository\ScheduleEventRepository;
use CroudTech\RecurringTaskScheduler\Repository\ScheduleRepository;
use CroudTech\RecurringTaskScheduler\Subscribers\ScheduleSubscriber;
use CroudTech\RecurringTaskScheduler\Traits\ScheduleableTrait;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleEventTransformer;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleTransformer;
use CroudTech\RecurringTaskScheduler\Console\Commands\ExecuteCommand;
use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\Repositories\Contracts\TransformerContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use CroudTech\RecurringTaskScheduler\Events\ScheduleExecuteEvent;
use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;
use CroudTech\RecurringTaskScheduler\Listeners\TriggerScheduleEventsListener;
use CroudTech\RecurringTaskScheduler\Listeners\TriggerScheduleEventCallback;

class RecurringTaskSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();
        $this->registerEvents();
        $this->app->booted(function () {
            $this->registerObservers();
        });

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        Validator::extend('is_scheduleable', function ($attribute, $value, $parameters, $validator) {
            return !empty($value) && class_exists($value) && in_array(ScheduleableContract::class, class_implements($value));
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $this->app->bind(ScheduleParserContract::class, function ($app, $args) {
            if (!isset($args['definition']['type']) || !isset($args['definition']['period'])) {
                throw new InvalidArgument(sprintf('No definition type was provided'));
            }

            $classname = sprintf('\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\%s\%s', ucfirst(camel_case($args['definition']['type'])), ucfirst(camel_case($args['definition']['period'])));

            if (isset($args['definition']['modifier']) && !empty($args['definition']['modifier'])) {
                $modifier_namespace = ucfirst(camel_case($args['definition']['modifier']));
                $modifier_classname = sprintf('\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\%s\%s\%s', ucfirst(camel_case($args['definition']['type'])), ucfirst(camel_case($args['definition']['period'])), $modifier_namespace);
                if (class_exists($modifier_classname)) {
                    $classname = $modifier_classname;
                } else {
                    \Log::warning(sprintf('Schedule definition is attemting to apply a non-existent modifier "%s" classname "%s"', $args['definition']['modifier'], $modifier_classname));
                }
            }

            if (!class_exists($classname)) {
                throw new InvalidArgument(sprintf('There is no ScheduleParserContract implementation that matches the definition type "%s" "%s" tried to load class %s', $args['definition']['type'], $args['definition']['period'], $classname));
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
        $this->app->when(ScheduleEventsNestedController::class)
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

    protected function registerCommands()
    {
        $this->commands([
            ExecuteCommand::class
        ]);
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


        $this->publishes([
            __DIR__.'/../config/scheduleable.php' => config_path('scheduleable.php'),
        ]);
    }

    public function registerEvents()
    {
        Event::subscribe(ScheduleSubscriber::class);
        Event::listen(ScheduleExecuteEvent::class, TriggerScheduleEventsListener::class);
    }

    /**
     * Register model event observers
     *
     * @return void
     */
    public function registerObservers()
    {
        Schedule::observe(ScheduleObserver::class);
        ScheduleEvent::observe(ScheduleEventObserver::class);
    }
}
