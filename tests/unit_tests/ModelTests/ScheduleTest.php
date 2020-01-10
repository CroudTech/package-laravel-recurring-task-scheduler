<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ModelTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    public function testSave()
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
        $this->assertInternalType('integer', $schedule->id);
    }

    /**
     * Check schedule events relationship
     *
     */
    public function testScheduleEvents()
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
        $schedule_event = $schedule->scheduleEvents()->save(new \CroudTech\RecurringTaskScheduler\Model\ScheduleEvent([
            'date' => Carbon::parse('2017-01-01 09:00:00'),
        ]));
        $schedule_event->save();
        $this->assertEquals($schedule->id, $schedule_event->schedule_id);
    }

    /**
     * Check that schedule events are generated when they should be
     *
     * @dataProvider definitionsProvider
     */
    public function testScheduleEventsGeneration($definition, $expected)
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $schedule = $repository->createFromScheduleDefinition(json_decode($definition, true), $scheduleable);
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $this->assertEquals(count($expected), $schedule->scheduleEvents()->count(), sprintf('Failed with definition %s', $definition));
    }

    /**
     * Check that schedule events are generated when they should be
     *
     * @dataProvider definitionsProvider
     */
    public function testScheduleEventsGenerationOnUpdate($definition, $expected)
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $schedule = $repository->createFromScheduleDefinition(json_decode($definition, true), $scheduleable);
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $this->assertEquals(count($expected), $schedule->scheduleEvents()->count());
    }

    /**
     * Load json definitions from data file
     *
     * @return array
     */
    public function definitionsProvider() : array
    {
        return include $this->test_root . '/test_data/integration_test_json_definitions.php';
    }
}
