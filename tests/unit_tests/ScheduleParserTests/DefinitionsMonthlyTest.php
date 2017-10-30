<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

/**
 * Check scheduler periods:
 *
 * Daily
 *  - Every N days {period, interval}
 *  - Every Workday {period, interval, modifier}
 *  - Every DOW {period, interval}
 * Weekly
 *  - N weeks {period, interval}
 *  - On DOW {period, interval, modifier}
 * Same day each month
 *  - Day N of every N months {period, interval, modifier}
 * Same week each month
 *  – every N months on the (1,2,3,4,last) DOW {period, interval, modifier}
 * Same day each year
 *  – Day Month {period, interval, modifier}
 * Same week each year
 *  – (1,2,3,4,last) DOW or Month {period, interval, modifier}
 *
 * Examples
 */
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
        try {
            $generated_dates_array = $generated_dates->map(
                function ($date) {
                    return $date->format('c');
                }
            )->toArray();
            $this->assertEquals($expected, $generated_dates_array);
        } catch (\Exception $e) {
            dd($definition, $generated_dates_array, $expected);
        }
    }

    public function definitionsProvider()
    {
        return include $this->test_root . '/test_data/schedule_definitions_monthly.php';
    }
}
