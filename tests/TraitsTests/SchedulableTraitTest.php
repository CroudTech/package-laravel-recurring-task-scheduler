<?php
namespace CroudTech\RecurringTaskScheduler\Tests\Traits;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleTraitTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetSchedule()
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestSchedulable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-01 00:00:00';
        $schedule->period = 'days';
        $schedule->schedulable()->associate($scheduleable);
        $schedule->save();

        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Model\Schedule::class, $scheduleable->schedule->first());
    }
}
