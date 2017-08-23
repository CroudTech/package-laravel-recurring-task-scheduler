<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ModelTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleEventTest extends TestCase
{
    use DatabaseMigrations;

    public function testScheduleEventCreation()
    {
        Carbon::setTestNow(Carbon::parse('2017-01-01 00:00:00'));
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-01 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $schedule_event = \CroudTech\RecurringTaskScheduler\Model\ScheduleEvent::create(['schedule_id' => $schedule->id, 'date' => Carbon::parse($schedule->range_start)]);
        $schedule_event_array = $schedule_event->fresh()->toArray();
        ksort($schedule_event_array);
        $expected_event_array = [
            'created_at' => '2017-01-01 00:00:00',
            'date' => '2017-01-01 00:00:00',
            'deleted_at' => NULL,
            'id' => 1,
            'modified' => false,
            'original_date' => '2017-01-01 00:00:00',
            'schedule_id' => '1',
            'trigger_success' => NULL,
            'triggered_at' => NULL,
            'updated_at' => '2017-01-01 00:00:00',
        ];
        $this->assertEquals($expected_event_array, $schedule_event_array);
    }
}
