<?php
namespace CroudTech\RecurringTaskScheduler\Tests\RepositoryTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

class ScheduleRepositoryTest extends TestCase
{
    /**
     * Make sure our provider is returning an instance of the correct repository
     *
     * @return void
     */
    public function testServiceProvider()
    {
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Repository\ScheduleRepository::class, $repository);
    }
}
