<?php
namespace CroudTech\RecurringTaskScheduler\Tests\Traits;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleTraitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Check that the polymorphic shedulable relationship is working correctly
     *
     */
    public function testScheduleRelationship()
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-01 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Model\Schedule::class, $scheduleable->schedule->first());
    }

    public function testTrigger()
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-01 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();
        $schedule_event = $schedule->scheduleEvents()->create([
            'date' => Carbon::parse('2017-01-01 09:00:00'),
        ]);
        $schedule_event->save();
        $this->assertNull($schedule_event->fresh()->triggered_at);
        $trigger_date = \Carbon\Carbon::now();
        $schedule_event->trigger();

        $this->assertNotNull($schedule_event->fresh()->triggered_at);
        $this->assertTrue($schedule->fresh()->triggered_at->gte($trigger_date));
        $this->assertEquals($schedule_event->fresh()->triggered_at, $schedule->fresh()->triggered_at);
    }
}
