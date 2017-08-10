<?php
namespace CroudTech\RecurringTaskScheduler\Tests;

use Carbon\Carbon;

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
class ScheduleParserTest extends TestCase
{
    /**
     * Check that the expected dates are returned from the provided schedule definition array
     *
     * @return void
     */
    public function testParseDateFromArrayDaily()
    {

        $schedule_definition = [
            'timezone' => 'EDT',
            'range' => [
                Carbon::parse('2017-08-01 00:00:00'), // Tuesday 1st August 2017
                Carbon::parse('2017-08-30 23:59:59'), // Wednesday 30th August 2017
            ],
            'type' => 'periodic',
            'period' => 'days',
            'interval' => 5,
            'time_of_day' => '09:30:00',
        ];

        $schedule_definition['range'][0]->setTimezone($schedule_definition['timezone']);
        $schedule_definition['range'][1]->setTimezone($schedule_definition['timezone']);

        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($schedule_definition);
        $generated_dates = collect($parser->getDates());
        $this->assertEquals($schedule_definition['range'][0], $generated_dates->first()); // Check that the first date is equal to the start of the range
        $this->assertFalse($generated_dates->last() > $schedule_definition['range'][1]); // Check that the last date is not after the end of the range
        $test_start = $schedule_definition['range'][0]->copy();

        foreach ($generated_dates as $generated_date) {
            $this->assertEquals($test_start, $generated_date);
            $this->assertEquals($schedule_definition['time_of_day'], $generated_date->time());
            $test_start->add(sprintf('%s %s', $schedule_definition['interval'], $schedule_definition['period']));
        }
    }
}
