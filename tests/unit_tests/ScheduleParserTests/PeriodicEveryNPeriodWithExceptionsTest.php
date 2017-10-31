<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;


class PeriodicEveryNPeriodWithExceptionsTest extends TestCase
{
    /**
     * Test daily periodic definition
     *
     * @dataProvider testDailyEveryNDaysProvider
     * @group DEV
     */
    public function testDailyEveryNDays($definition, $expected_dates)
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition);
        $generated_dates = collect($parser->getDates());

        foreach ($generated_dates as $generated_date) {
            $day_name = strtolower($generated_date->format('D'));
            $month_name = strtolower($generated_date->format('M'));
            if (isset($definition['days'])) {
                $this->assertTrue(array_key_exists($day_name, $definition['days']), 'Day should not be in generated dates');
                $this->assertTrue($parser->getDefinition()['days'][$day_name], 'Day should not be in generated dates');
            }
            if (isset($definition['months'])) {
                $this->assertTrue(array_key_exists($month_name, $definition['months']), 'Month should not be in generated dates');
                $this->assertTrue($parser->getDefinition()['months'][$month_name], 'Month should not be in generated dates');
            }
        }
        $this->assertEquals(
            $expected_dates,
            $generated_dates->map(
                function ($date) {
                    return $date->format('c');
                }
            )->toArray(),
            'Expected dates did not match for definition ' . var_export($definition, true)
        );
    }

    /**
     * Test definitions
     *
     * @return array
     */
    public function testDailyEveryNDaysProvider()
    {
        return collect(
            [
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-01-30 23:59:59',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                    'days' => [
                        'mon' => 1,
                    ],
                ],
                [
                    "2017-01-02T09:30:00+00:00",
                    "2017-01-09T09:30:00+00:00",
                    "2017-01-16T09:30:00+00:00",
                    "2017-01-23T09:30:00+00:00",
                    "2017-01-30T09:30:00+00:00",
                ],
            ],
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-12-31 23:59:59',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                    'days' => [
                        'mon' => 1,
                        'fri' => 1,
                    ],
                    'months' => [
                        'jan' => true,
                    ],
                ],
                [
                    "2017-01-02T09:30:00+00:00",
                    "2017-01-06T09:30:00+00:00",
                    "2017-01-09T09:30:00+00:00",
                    "2017-01-13T09:30:00+00:00",
                    "2017-01-16T09:30:00+00:00",
                    "2017-01-20T09:30:00+00:00",
                    "2017-01-23T09:30:00+00:00",
                    "2017-01-27T09:30:00+00:00",
                    "2017-01-30T09:30:00+00:00",
                ],
            ],
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2017-12-31 23:59:59',
                    ],
                    'type' => 'periodic',
                    'period' => 'days',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                    'days' => [
                        'mon' => true,
                    ],
                    'months' => [
                        'jan' => true,
                        'mar' => true,
                    ],
                ],
                [
                    "2017-01-02T09:30:00+00:00",
                    "2017-01-09T09:30:00+00:00",
                    "2017-01-16T09:30:00+00:00",
                    "2017-01-23T09:30:00+00:00",
                    "2017-01-30T09:30:00+00:00",
                    "2017-03-06T09:30:00+00:00",
                    "2017-03-13T09:30:00+00:00",
                    "2017-03-20T09:30:00+00:00",
                    "2017-03-27T09:30:00+01:00",
                ],
            ],
            [
                [
                    'timezone' => 'Europe/London',
                    'range' => [
                        '2017-01-02 00:00:00',
                        '2017-12-31 23:59:59',
                    ],
                    'type' => 'periodic',
                    'period' => 'months',
                    'interval' => 1,
                    'time_of_day' => '09:30:00',
                    'days' => [
                        'mon' => 1,
                    ],
                ],
                [
                    "2017-01-02T09:30:00+00:00",
                    "2017-10-02T09:30:00+01:00",
                ],
            ],
            ]
        )->map(
            function ($row) {
                foreach ($row[1] as $k => $expected_date) {
                    $row[1][$k] = \Carbon\Carbon::parse($expected_date)->setTimezone('UTC')->format('c');
                }
                    return $row;
            }
        )->toArray();
    }
}
