<?php
namespace CroudTech\RecurringTaskScheduler\Tests\App\Providers;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableRepositoryContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Http\Controllers;
use CroudTech\RecurringTaskScheduler\Tests\App\Http\Controllers\NestedScheduleableController;
use CroudTech\RecurringTaskScheduler\Tests\App\Repositories\TestScheduleableRepository;
use CroudTech\RecurringTaskScheduler\Tests\App\Transformers\TestScheduleableTransformer;
use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\Repositories\Contracts\TransformerContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(NestedScheduleableController::class)
            ->needs(RepositoryContract::class)
            ->give(ScheduleRepositoryContract::class);

        $this->app->when(NestedScheduleableController::class)
            ->needs(ScheduleableRepositoryContract::class)
            ->give(TestScheduleableRepository::class);

        $this->app->bind(ScheduleableRepositoryContract::class, TestScheduleableRepository::class);

        $this->app->when(TestScheduleableRepository::class)
            ->needs(TransformerContract::class)
            ->give(function () {
                return $this->app->make(TestScheduleableTransformer::class);
            });
    }
}
