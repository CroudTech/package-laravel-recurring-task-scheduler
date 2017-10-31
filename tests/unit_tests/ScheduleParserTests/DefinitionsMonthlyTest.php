<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;


class DefinitionsMonthlyTest extends TestCase
{
    /**
     * @dataProvider definitionsProvider
     */
    public function testDefinitions($definition, $expected)
    {
        $definition = json_decode($definition, true);
        if (isset($definition['definition_description'])) {
            \Log::debug($definition['definition_description']);
        }
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition);
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
        return include $this->test_root . '/test_data/schedule_definitions_monthly.php';
    }
}
