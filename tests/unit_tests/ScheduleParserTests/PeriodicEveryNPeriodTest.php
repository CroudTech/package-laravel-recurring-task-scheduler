<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Tests\BrowserKitTestCase;


class PeriodicEveryNPeriodTest extends BrowserKitTestCase
{
    /**
     * Test daily periodic definition
     *
     * @dataProvider dailyEveryNDaysProvider
     */
    public function testDailyEveryNDays($definition, $expected_dates)
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition);
        $generated_dates = collect($parser->getDates());

        $this->assertTrue((new Carbon($definition['range'][0], $definition['timezone']))->isSameDay($generated_dates->first()->setTimezone($definition['timezone'])), 'First generated date is not the same day as the first day of the range'); // Check that the first date is equal to the start of the range

        $this->assertTrue($generated_dates->last()->lte(new Carbon($definition['range'][1], $definition['timezone'])), 'Last generated date is not before the last date of the range'); // Check that the last date is not after the end of the range
        $test_date = (new Carbon($definition['range'][0], $definition['timezone']))->setTime(...explode(':', $definition['time_of_day']));

        foreach ($generated_dates as $generated_date) {
            $this->assertTrue($test_date->eq($generated_date), $test_date->format('c') . ' ' . $generated_date->format('c'));
            $this->assertEquals($definition['time_of_day'], $generated_date->copy()->setTimezone($definition['timezone'])->format('H:i:s'), $generated_date->format('c'));
            $modify_method = sprintf('add%s', ucfirst(camel_case($definition['period'])));
            $test_date->$modify_method($definition['interval'])->setTime(...explode(':', $definition['time_of_day']));
        }

        $this->assertEquals(
            $expected_dates, $generated_dates->map(
                function ($date) use ($definition) {
                    return $date->copy()->setTimezone($definition['timezone'])->format('c');
                }
            )->toArray()
        );
    }

    /**
     * Test definitions
     *
     * @return array
     */
    public function dailyEveryNDaysProvider()
    {
        return [
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-01-30 23:59:59',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 5,
                    'time_of_day' => '09:30:00',
                ],
                [
                    "2017-01-01T09:30:00+00:00",
                    "2017-01-06T09:30:00+00:00",
                    "2017-01-11T09:30:00+00:00",
                    "2017-01-16T09:30:00+00:00",
                    "2017-01-21T09:30:00+00:00",
                    "2017-01-26T09:30:00+00:00",
                ],
            ],
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-03-24 00:00:00',
                        '2017-04-24 00:00:00',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 5,
                    'time_of_day' => '09:30:00',
                ],
                [
                    '2017-03-24T09:30:00+00:00',
                    '2017-03-29T09:30:00+01:00',
                    '2017-04-03T09:30:00+01:00',
                    '2017-04-08T09:30:00+01:00',
                    '2017-04-13T09:30:00+01:00',
                    '2017-04-18T09:30:00+01:00',
                    '2017-04-23T09:30:00+01:00',
                ],
            ],
            [
                [
                    'timezone' => 'Europe/Paris',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-01-30 23:59:59',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 5,
                    'time_of_day' => '09:30:00',
                ],
                [
                    '2017-01-01T09:30:00+01:00',
                    '2017-01-06T09:30:00+01:00',
                    '2017-01-11T09:30:00+01:00',
                    '2017-01-16T09:30:00+01:00',
                    '2017-01-21T09:30:00+01:00',
                    '2017-01-26T09:30:00+01:00',
                ],
            ],
            [
                [
                    'timezone' => 'Europe/Paris',
                    'range' => [
                        '2017-03-24 00:00:00',
                        '2017-04-24 00:00:00',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 5,
                    'time_of_day' => '09:30:00',
                ],
                [
                    '2017-03-24T09:30:00+01:00',
                    '2017-03-29T09:30:00+02:00',
                    '2017-04-03T09:30:00+02:00',
                    '2017-04-08T09:30:00+02:00',
                    '2017-04-13T09:30:00+02:00',
                    '2017-04-18T09:30:00+02:00',
                    '2017-04-23T09:30:00+02:00',
                ],
            ],
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-01-02 00:00:00',
                        '2017-05-03 00:00:00',
                    ],
                    'type' => 'periodic',
                    'period' => 'months',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                ],
                [
                    '2017-01-02T09:30:00+00:00',
                    '2017-02-02T09:30:00+00:00',
                    '2017-03-02T09:30:00+00:00',
                    '2017-04-02T09:30:00+01:00',
                    '2017-05-02T09:30:00+01:00',
                ],
            ],
            [
                [
                    'timezone' => 'Australia/Sydney',
                    'range' => [
                        '2017-01-02 00:00:00',
                        '2017-05-03 00:00:00',
                    ],
                    'type' => 'periodic',
                    'period' => 'months',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                ],
                [
                    '2017-01-02T09:30:00+11:00',
                    '2017-02-02T09:30:00+11:00',
                    '2017-03-02T09:30:00+11:00',
                    '2017-04-02T09:30:00+10:00',
                    '2017-05-02T09:30:00+10:00',
                ],
            ],
            [
                [
                    'timezone' => 'Africa/Cairo', // Egypt doesn't use daylight saving
                    'range' => [
                        '2017-01-02 00:00:00',
                        '2017-05-03 00:00:00',
                    ],
                    'type' => 'periodic',
                    'period' => 'months',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                ],
                [
                    '2017-01-02T09:30:00+02:00',
                    '2017-02-02T09:30:00+02:00',
                    '2017-03-02T09:30:00+02:00',
                    '2017-04-02T09:30:00+02:00',
                    '2017-05-02T09:30:00+02:00',
                ],
            ],
        ];
    }
}
