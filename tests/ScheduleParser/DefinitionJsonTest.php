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
        return [
            [   // Daily [every other day]
                '{ "timezone": "Europe/London", "range": { "start": "2017-08-16", "end": "2017-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": "2", "period": "days", "day_of_month": false, "week_of_month": false, "days": {}, "months": {} }',
                [
                    '2017-08-16T09:00:00+01:00',
                    '2017-08-18T09:00:00+01:00',
                    '2017-08-20T09:00:00+01:00',
                    '2017-08-22T09:00:00+01:00',
                    '2017-08-24T09:00:00+01:00',
                    '2017-08-26T09:00:00+01:00',
                    '2017-08-28T09:00:00+01:00',
                    '2017-08-30T09:00:00+01:00',
                    '2017-09-01T09:00:00+01:00',
                    '2017-09-03T09:00:00+01:00',
                    '2017-09-05T09:00:00+01:00',
                    '2017-09-07T09:00:00+01:00',
                    '2017-09-09T09:00:00+01:00',
                    '2017-09-11T09:00:00+01:00',
                    '2017-09-13T09:00:00+01:00',
                    '2017-09-15T09:00:00+01:00',
                    '2017-09-17T09:00:00+01:00',
                    '2017-09-19T09:00:00+01:00',
                    '2017-09-21T09:00:00+01:00',
                    '2017-09-23T09:00:00+01:00',
                    '2017-09-25T09:00:00+01:00',
                    '2017-09-27T09:00:00+01:00',
                    '2017-09-29T09:00:00+01:00',
                    '2017-10-01T09:00:00+01:00',
                    '2017-10-03T09:00:00+01:00',
                    '2017-10-05T09:00:00+01:00',
                    '2017-10-07T09:00:00+01:00',
                    '2017-10-09T09:00:00+01:00',
                    '2017-10-11T09:00:00+01:00',
                    '2017-10-13T09:00:00+01:00',
                    '2017-10-15T09:00:00+01:00',
                    '2017-10-17T09:00:00+01:00',
                    '2017-10-19T09:00:00+01:00',
                    '2017-10-21T09:00:00+01:00',
                    '2017-10-23T09:00:00+01:00',
                    '2017-10-25T09:00:00+01:00',
                    '2017-10-27T09:00:00+01:00',
                    '2017-10-29T09:00:00+00:00',
                    '2017-10-31T09:00:00+00:00',
                    '2017-11-02T09:00:00+00:00',
                    '2017-11-04T09:00:00+00:00',
                    '2017-11-06T09:00:00+00:00',
                    '2017-11-08T09:00:00+00:00',
                    '2017-11-10T09:00:00+00:00',
                    '2017-11-12T09:00:00+00:00',
                    '2017-11-14T09:00:00+00:00',
                    '2017-11-16T09:00:00+00:00',
                    '2017-11-18T09:00:00+00:00',
                    '2017-11-20T09:00:00+00:00',
                    '2017-11-22T09:00:00+00:00',
                    '2017-11-24T09:00:00+00:00',
                    '2017-11-26T09:00:00+00:00',
                    '2017-11-28T09:00:00+00:00',
                    '2017-11-30T09:00:00+00:00',
                ],
            ],
        ];
    }
}
