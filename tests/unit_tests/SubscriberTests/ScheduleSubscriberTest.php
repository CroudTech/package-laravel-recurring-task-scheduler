<?php
namespace CroudTech\RecurringTaskScheduler\Tests\SubscriberTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;

class ScheduleSubscriberTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Make sure the trigger event is dispatched and the subscriber runs the listener callback
     *
     * @return void
     */
    public function testOnScheduleEventTrigger()
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
        $schedule_event->scheduleEventTrigger();
        $this->assertNotNull($schedule_event->fresh()->triggered_at);
        $this->assertTrue($schedule_event->fresh()->trigger_success);
    }

    /**
     * Make sure the trigger event is dispatched and the subscriber runs the listener callback
     *
     * @return void
     */
    public function testOnScheduleEventTriggerFailed()
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__, 'test_success' => false]);
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
        $schedule_event->scheduleEventTrigger();
        $this->assertNotNull($schedule_event->fresh()->triggered_at);
        $this->assertFalse($schedule_event->fresh()->trigger_success);
    }
}
