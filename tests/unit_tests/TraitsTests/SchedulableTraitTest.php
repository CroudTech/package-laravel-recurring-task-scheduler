<?php
namespace CroudTech\RecurringTaskScheduler\Tests\Traits;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;

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

    /**
     * Make sure the trigger event is dispatched
     *
     * @return void
     */
    public function testTriggerEventDispatch()
    {
        Event::fake();
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
            'original_date' => Carbon::parse('2017-01-01 08:00:00'),
            'date' => Carbon::parse('2017-01-01 09:00:00'),
        ]);
        $schedule_event->save();
        $schedule_event->trigger();
        Event::assertDispatched(\CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent::class, function ($e) use ($schedule_event) {
            return $e->schedule_event->id === $schedule_event->id;
        });
    }
}
