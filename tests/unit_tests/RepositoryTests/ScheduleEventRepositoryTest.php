<?php
namespace CroudTech\RecurringTaskScheduler\Tests\RepositoryTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;

class ScheduleEventRepositoryTest extends BrowserKitTestCase
{
    /**
     * Make sure our provider is returning an instance of the correct repository
     *
     * @return void
     */
    public function testServiceProvider()
    {
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract::class);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Repository\ScheduleEventRepository::class, $repository);
    }
}
