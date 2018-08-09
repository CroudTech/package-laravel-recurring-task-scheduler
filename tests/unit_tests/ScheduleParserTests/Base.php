<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;

/**
 * Check scheduler periods:
 */
abstract class Base extends BrowserKitTestCase
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
        try {
            $this->assertEquals($expected, $generated_dates_array);
        } catch (\Exception $e) {
            throw $e;
            dd($definition, $generated_dates_array, $expected);
        }
    }
}
