<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

/**
 * Check scheduler periods:
 *
 */
class DefinitionsWeeklyTest extends TestCase
{
    /**
     * @dataProvider definitionsProvider
     */
    public function testDefinitions($definition, $expected)
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition = json_decode($definition, true));
        $generated_dates = collect($parser->getDates());
        $generated_dates_array = $generated_dates->map(
            function ($date) {
                return $date->format('c');
            }
        )->toArray();
        $this->assertEquals($expected, $generated_dates_array);
    }

    public function definitionsProvider()
    {
        return include $this->test_root . '/test_data/schedule_definitions_weekly.php';
    }
}
