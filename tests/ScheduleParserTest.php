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
     * Check the service provider returns the correct class for the specified contract
     *
     * @return void
     */
    public function testServiceProvider()
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract::class);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser::class, $parser);
    }

    /**
     * Check that the expected dates are returned from the provided schedule definition array
     *
     * @dataProvider scheduleDefinitionProviderDaily
     * @return void
     */
    public function testParseDateFromArrayDaily($schedule_definition)
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract::class);
        $generated_dates = collect($parser->getDatesFromDefinition($schedule_definition));
        $this->assertEquals($schedule_definition['range'][0], $generated_dates->first()); // Check that the first date is equal to the start of the range
        $this->assertFalse($generated_dates->last() > $schedule_definition['range'][1]);
        $test_start = $schedule_definition['range'][0]->copy();

        foreach ($generated_dates as $generated_date) {
            $this->assertEquals($test_start, $generated_date);
            $this->assertEquals($schedule_definition['time_of_day'], $generated_date->time());
            $test_start->add(sprintf('%s %s', $schedule_definition['interval'], $schedule_definition['period']));
        }

        // $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract::class);
        // $actual = $parser->getDatesFromDefinition($schedule_definition);
        // foreach ($expected as $expected_date_key => $expected_date) {
        //     $this->assertArrayHasKey($expected_date_key, $actual);
        //     $this->assertEquals($actual[$expected_date_key], $expected_date);
        // }
    }

    /**
     * Provides schedule definitions that cover all possible period definitions
     *
     * Daily – Every N days, Every Workday, Every DOW,
     * Weekly – N weeks – On DOW
     * Same day each month – Day N of every N months
     * Same week each month – every N months on the (1,2,3,4,last) DOW
     * Same day each year – Day Month
     * Same week each year – (1,2,3,4,last) DOW or Month
     *
     *
     * @return void
     */
    public function scheduleDefinitionProviderDaily()
    {
        return [
            [
                [
                    'timezone' => 'EDT',
                    'range' => [
                        Carbon::parse('2017-08-09 13:00:00'),
                        Carbon::parse('2017-08-09 13:00:00'),
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 5,
                    'time_of_day' => '09:30:00',
                ]
            ]
        ];

    }
}
