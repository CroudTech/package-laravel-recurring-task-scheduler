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
class DefinitionsTestWeekly extends TestCase
{
    /**
     * @dataProvider definitionsProvider
     */
    public function testDefinitions($definition, $expected)
    {
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
        return [
            // N weeks
            [
                [
                    'type' => 'periodic',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-01-31 23:59:59',
                    ],
                    'period' => 'weeks',
                    'interval' => 1
                ],
                [
                    '2017-01-01T09:00:00+00:00',
                    // '2017-01-02T09:00:00+00:00',
                    // '2017-01-03T09:00:00+00:00',
                    // '2017-01-04T09:00:00+00:00',
                    // '2017-01-05T09:00:00+00:00',
                    // '2017-01-06T09:00:00+00:00',
                    //'2017-01-07T09:00:00+00:00',
                    '2017-01-08T09:00:00+00:00',
                    // '2017-01-09T09:00:00+00:00',
                    // '2017-01-10T09:00:00+00:00',
                    // '2017-01-11T09:00:00+00:00',
                    // '2017-01-12T09:00:00+00:00',
                    // '2017-01-13T09:00:00+00:00',
                    // '2017-01-14T09:00:00+00:00',
                    '2017-01-15T09:00:00+00:00',
                    // '2017-01-16T09:00:00+00:00',
                    // '2017-01-17T09:00:00+00:00',
                    // '2017-01-18T09:00:00+00:00',
                    // '2017-01-19T09:00:00+00:00',
                    // '2017-01-20T09:00:00+00:00',
                    // '2017-01-21T09:00:00+00:00',
                    '2017-01-22T09:00:00+00:00',
                    // '2017-01-23T09:00:00+00:00',
                    // '2017-01-24T09:00:00+00:00',
                    // '2017-01-25T09:00:00+00:00',
                    // '2017-01-26T09:00:00+00:00',
                    // '2017-01-27T09:00:00+00:00',
                    // '2017-01-28T09:00:00+00:00',
                    '2017-01-29T09:00:00+00:00',
                    // '2017-01-30T09:00:00+00:00',
                    // '2017-01-31T09:00:00+00:00',
                ],
            ],
            // On DOW
            [
                [
                    'type' => 'periodic',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-01-31 23:59:59',
                    ],
                    'period' => 'days',
                    'days' => [
                        'mon' => true,
                    ],
                    'interval' => 1
                ],
                [
                    //'2017-01-01T09:00:00+00:00',
                    '2017-01-02T09:00:00+00:00',
                    // '2017-01-03T09:00:00+00:00',
                    // '2017-01-04T09:00:00+00:00',
                    // '2017-01-05T09:00:00+00:00',
                    // '2017-01-06T09:00:00+00:00',
                    //'2017-01-07T09:00:00+00:00',
                    //'2017-01-08T09:00:00+00:00',
                    '2017-01-09T09:00:00+00:00',
                    // '2017-01-10T09:00:00+00:00',
                    // '2017-01-11T09:00:00+00:00',
                    // '2017-01-12T09:00:00+00:00',
                    // '2017-01-13T09:00:00+00:00',
                    // '2017-01-14T09:00:00+00:00',
                    //'2017-01-15T09:00:00+00:00',
                    '2017-01-16T09:00:00+00:00',
                    // '2017-01-17T09:00:00+00:00',
                    // '2017-01-18T09:00:00+00:00',
                    // '2017-01-19T09:00:00+00:00',
                    // '2017-01-20T09:00:00+00:00',
                    // '2017-01-21T09:00:00+00:00',
                    //'2017-01-22T09:00:00+00:00',
                    '2017-01-23T09:00:00+00:00',
                    // '2017-01-24T09:00:00+00:00',
                    // '2017-01-25T09:00:00+00:00',
                    // '2017-01-26T09:00:00+00:00',
                    // '2017-01-27T09:00:00+00:00',
                    // '2017-01-28T09:00:00+00:00',
                    //'2017-01-29T09:00:00+00:00',
                    '2017-01-30T09:00:00+00:00',
                    // '2017-01-31T09:00:00+00:00',
                ],
            ],
        ];
    }
}
