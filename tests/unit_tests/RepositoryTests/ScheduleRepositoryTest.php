<?php
namespace CroudTech\RecurringTaskScheduler\Tests\RepositoryTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleRepositoryTest extends TestCase
{
    use DatabaseMigrations;

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

    /**
     * Create schedule from definition
     *
     * @dataProvider definitionsProvider
     */
    public function testCreateScheduleFromDefinition($definition, $expected)
    {
        $this->migrate();
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = $repository->createFromScheduleDefinition(json_decode($definition, true), $scheduleable);
        $this->assertEquals(count($expected), $schedule->scheduleEvents()->count());

    }

    /**
     * Load json definitions from data file
     *
     * @return array
     */
    public function definitionsProvider() : array
    {
        return include $this->test_root . '/test_data/json_definitions.php';
    }
}
