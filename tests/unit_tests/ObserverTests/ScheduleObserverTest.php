<?php
namespace CroudTech\RecurringTaskScheduler\Tests\RepositoryTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleObserverTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /**
     * Make sure our provider is returning an instance of the correct repository
     *
     * @return void
     */
    public function testServiceProvider()
    {
        $observer = $this->app->make(\CroudTech\RecurringTaskScheduler\Observers\ScheduleObserver::class);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Observers\ScheduleObserver::class, $observer);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Transformer\ScheduleTransformer::class, $observer->getTransformer());
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Repository\ScheduleRepository::class, $observer->getRepository());
    }
}
