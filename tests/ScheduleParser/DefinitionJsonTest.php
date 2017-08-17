<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParser;

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
 *
 */
class DefinitionJsonTest extends TestCase
{
    /**
     * @dataProvider definitionsProvider
     */
    public function testDefinitions($definition_json, $expected)
    {
        $definition = json_decode($definition_json, true);
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition);
        $generated_dates = collect($parser->getDates());

        $this->assertEquals($expected, $generated_dates->map(
            function ($date) {
                return $date->format('c');
            }
        )->toArray());
    }

    public function definitionsProvider()
    {
        // $date_start = Carbon::parse('2017-08-16 09:00:00');
        // $date_end = Carbon::parse('2017-11-30 23:59:59');
        // while ($date_start->lte($date_end)) {
        //     echo sprintf('\'%s\','.PHP_EOL, $date_start->format('c'));
        //     $date_start->addDays(2);
        // }
        // die();
        // dd($this->app['config']);
        // dd(config('test.data_folder'));
            //dd($this->test_root . '/test_data/json_definitions.php');
        return include $this->test_root . '/test_data/json_definitions.php';
    }
}
