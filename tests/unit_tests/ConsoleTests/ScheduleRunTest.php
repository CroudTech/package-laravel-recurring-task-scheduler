<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ConsoleTests;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Artisan;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;

class ScheduleEventTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    public function testScheduleEventTriggerWithInvalidDate()
    {
        $this->expectsEvents(\CroudTech\RecurringTaskScheduler\Events\ScheduleExecuteEvent::class);
        $this->doesntExpectEvents(\CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent::class);

        Carbon::setTestNow(Carbon::parse('2017-01-01 00:00:00'));
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-05 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $invalid_schedule_date = Carbon::parse('2016-01-01 00:00:00');          // create testing date
        Carbon::setTestNow($invalid_schedule_date);


        Artisan::call('croudtech:schedule:execute');
    }

    public function testScheduleEventTriggerWithValidDate()
    {
        Carbon::setTestNow(Carbon::parse('2017-01-01 00:00:00'));
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['test_success' => true, 'name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-05 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $valid_schedule_date = Carbon::parse('2017-01-01 00:00:00');          // create testing date
        Carbon::setTestNow($valid_schedule_date);

        Artisan::call('croudtech:schedule:execute');
        $event_query = $schedule->scheduleEvents()->whereBetween('date', [$valid_schedule_date->copy()->startOfDay(), $valid_schedule_date->copy()->endOfDay()]);

        $this->assertEquals(1, $event_query->count());
        $event_query->each(function ($schedule_event) {
            $this->assertTrue($schedule_event->trigger_success);
        });
    }

    public function testScheduleEventTriggerEventPaused()
    {
        Carbon::setTestNow(Carbon::parse('2017-01-01 00:00:00'));
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['test_success' => true, 'name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-05 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $valid_schedule_date = Carbon::parse('2017-01-01 00:00:00');          // create testing date
        Carbon::setTestNow($valid_schedule_date);

        $scheduleEvent = ScheduleEvent::where('schedule_id', $schedule->id)->where('date', $valid_schedule_date)->first();
        $scheduleEvent->paused = true;
        $scheduleEvent->save();
        
        Artisan::call('croudtech:schedule:execute');
        $event_query = $schedule->scheduleEvents()->whereBetween('date', [$valid_schedule_date->copy()->startOfDay(), $valid_schedule_date->copy()->endOfDay()]);
        
        $this->assertEquals(1, $event_query->count());
        $event_query->each(function ($schedule_event) {
            $this->assertEquals(null, $schedule_event->trigger_success);
        });
    }
}
