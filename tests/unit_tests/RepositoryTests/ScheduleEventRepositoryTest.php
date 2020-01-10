<?php
namespace CroudTech\RecurringTaskScheduler\Tests\RepositoryTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleEventRepositoryTest extends BrowserKitTestCase
{
    use DatabaseMigrations;
    /**
     * Make sure our provider is returning an instance of the correct repository
     *
     * @return void
     */
    public function testServiceProvider()
    {
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract::class);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Repository\ScheduleEventRepository::class, $repository);
    }

    public function testScheduleExecution()
    {
        $scheduleRepository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $scheduleEventRepository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract::class);

        collect($this->getScheduleData())->each(function($definition) use ($scheduleRepository) {
            $scheduleRepository->createFromScheduleDefinition($definition);
        });

        $timestamp = Carbon::parse('2019-11-04 15:00:00', 'UTC');
        $events = $scheduleEventRepository->getEventsForTimestamp($timestamp);
        $this->assertEquals($events->count(), 3);

        $timestamp = Carbon::parse('2019-11-04 05:00:00', 'UTC');
        $events = $scheduleEventRepository->getEventsForTimestamp($timestamp);
        $this->assertEquals($events->count(), 2);
    }

    protected function getScheduleData()
    {
        return [
            [
                'range' => [
                    'start' => '2019-11-04 00:00:00',
                    'end' => '2019-11-04 23:59:59',
                ],
                'time_of_day' => '00:00',
                'timezone' => 'Australia/Sydney',
                'type' => 'periodic',
                'period' => 'days'
            ],
            [
                'range' => [
                    'start' => '2019-11-04',
                    'end' => '2019-11-04',
                ],
                'time_of_day' => '02:00',
                'timezone' => 'Europe/London',
                'type' => 'periodic',
                'period' => 'days'
            ],
            [
                'range' => [
                    'start' => '2019-11-04',
                    'end' => '2019-11-04',
                ],
                'time_of_day' => '22:00',
                'timezone' => 'America/New_York',
                'type' => 'periodic',
                'period' => 'days'
            ],
            [
                'range' => [
                    'start' => '2019-11-04',
                    'end' => '2019-11-04',
                ],
                'time_of_day' => '09:00',
                'timezone' => 'America/New_York',
                'type' => 'periodic',
                'period' => 'days'
            ],
        ];       
    }
}
