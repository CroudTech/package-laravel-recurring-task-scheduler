<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ModelTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleEventTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /**
     * Test creation of schedule event from model
     *
     */
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
            'deleted_at' => null,
            'id' => $schedule_event->id,
            'modified' => false,
            'original_date' => '2017-01-01 00:00:00',
            'schedule_id' => $schedule->id,
            'trigger_success' => null,
            'triggered_at' => null,
            'updated_at' => '2017-01-01 00:00:00',
            'paused' => false,
        ];
        $this->assertEquals($expected_event_array, $schedule_event_array);
    }

    /**
     * Test the todays events scope throws correct exception when grammar isn't supported
     *
     * @expectedException     \CroudTech\RecurringTaskScheduler\Exceptions\InvalidScopeException
     */
    public function testScopeTodaysEventsWithInvalidQueryGrammar()
    {
        $query = \CroudTech\RecurringTaskScheduler\Model\ScheduleEvent::query();
        $query->todaysEvents();
    }

    /**
     * Test the todays events scope throws correct exception when grammar isn't supported
     *
     */
    public function testScopeTodaysEventsWithMysqlGrammar()
    {
        $query = \CroudTech\RecurringTaskScheduler\Model\ScheduleEvent::query();
        $base_query = $query->getQuery();
        $refObject   = new \ReflectionObject( $base_query );
        $refProperty = $refObject->getProperty( 'grammar' );
        $refProperty->setAccessible( true );
        $refProperty->setValue($base_query, new \Illuminate\Database\Query\Grammars\MySqlGrammar);
        $query->todaysEvents();
        $this->assertEquals('select `ctrts_schedule_events`.* from `ctrts_schedule_events` inner join `ctrts_schedules` on `ctrts_schedule_events`.`schedule_id` = `ctrts_schedules`.`id` where date >= CONVERT_TZ(DATE_FORMAT(CONVERT_TZ(NOW(), \'UTC\', ctrts_schedules.timezone),"%Y-%m-%d 00:00:00"), ctrts_schedules.timezone, \'UTC\') and `ctrts_schedule_events`.`deleted_at` is null', $query->toSql());
    }
}
