<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

class BaseTest extends TestCase
{
    /**
     * Check that months and days are filtered correctly
     *
     * @dataProvider filterExceptionsDataProvider
     */
    public function testFilterExceptions($definition)
    {
        $parser = new PeriodicParser($definition);
        $this->assertNotEmpty($parser->getDates());
        collect($parser->getDates())->each(
            function ($date) use ($definition) {
                if (isset($definition['months'])) {
                    $this->assertArrayHasKey(strtolower($date->format('M')), $definition['months']);
                }
                if (isset($definition['days'])) {
                    $this->assertArrayHasKey(strtolower($date->format('D')), $definition['days']);
                }
            }
        );
    }

    /**
     * Test that the getDefinition returns a correctly parsed definition array
     *
     * @dataProvider definitionDaysProvider
     */
    public function testGetDefinitionDays($definition, $expected_days)
    {
        $parser = new PeriodicParser($definition);
        $this->assertArrayHasKey('days', $parser->getDefinition());
        $this->assertEquals($expected_days, $parser->getDefinition()['days']);
    }

    /**
     * Test that the getDefinition returns a correctly parsed definition array
     *
     * @dataProvider definitionMonthsProvider
     */
    public function testGetDefinitionMonths($definition, $expected_days)
    {
        $parser = new PeriodicParser($definition);
        $this->assertArrayHasKey('months', $parser->getDefinition());
        $this->assertEquals($expected_days, $parser->getDefinition()['months']);
    }

    /**
     * Check that the parser gives the correct value for time_of_day
     */
    public function testGetTimeOfday()
    {
        $parser = new PeriodicParser([]);
        $this->assertEquals('09:00:00', $parser->getTimeOfDay());
        $parser = new PeriodicParser(['time_of_day' => '12:00:00']);
        $this->assertEquals('12:00:00', $parser->getTimeOfDay());
    }

    /**
     * Check that the parser gives the correct value for interval
     */
    public function testGetInterval()
    {
        $parser = new PeriodicParser([]);
        $this->assertEquals(1, $parser->getInterval());
        $parser = new PeriodicParser(['interval' => '5']);
        $this->assertEquals(5, $parser->getInterval());
    }

    /**
     * Test default ranges
     */
    public function testGetRangeMethodsDefaultRange()
    {
        $definition = [
            'type' => 'periodic',
        ];
        $now = Carbon::now();
        $parser = new PeriodicParser($definition);
        $this->assertEquals($now->setTime(0, 0, 0)->format('c'), $parser->getRangeStart()->format('c'));
        $this->assertEquals($now->setTime(23, 59, 59)->addYear(1)->format('c'), $parser->getRangeEnd()->format('c'));
    }

    /**
     * Test daily periodic definition
     *
     * @dataProvider timezonesProvider
     */
    public function testGetRangeMethods($timezone, $range_start, $range_end)
    {
        $definition = [
            'timezone' => $timezone,
            'range' => [
                $range_start,
                $range_end,
            ],
            'type' => 'periodic',
            'period' => 'days',
            'interval' => 5,
            'time_of_day' => '09:30:00',
        ];

        $parser = new PeriodicParser($definition);

        $offset = (new \DateTimeZone($timezone))->getOffset(new Carbon($range_start, $timezone));
        $this->assertInstanceOf(Carbon::class, $parser->getRangeStart());
        $this->assertEquals($definition['timezone'], $parser->getRangeStart()->timezoneName);
        $this->assertEquals(Carbon::parse($range_start)->subSeconds($offset)->format('Y-m-d H:i:s'), $parser->getRangeStart()->setTimezone('UTC')->format('Y-m-d H:i:s'));
        $this->assertEquals(Carbon::parse($range_start)->subSeconds($offset)->format('Y-m-d H:i:s'), $parser->getRangeStartUTC()->format('Y-m-d H:i:s'));
        $this->assertEquals($range_start, $parser->getRangeStart()->format('Y-m-d H:i:s'));

        $offset = (new \DateTimeZone($timezone))->getOffset(new Carbon($range_end, $timezone));
        $this->assertInstanceOf(Carbon::class, $parser->getRangeEnd());
        $this->assertEquals($definition['timezone'], $parser->getRangeEnd()->timezoneName);
        $this->assertEquals(Carbon::parse($range_end)->subSeconds($offset)->format('Y-m-d H:i:s'), $parser->getRangeEnd()->setTimezone('UTC')->format('Y-m-d H:i:s'));
        $this->assertEquals(Carbon::parse($range_end)->subSeconds($offset)->format('Y-m-d H:i:s'), $parser->getRangeEndUTC()->format('Y-m-d H:i:s'));
        $this->assertEquals($range_end, $parser->getRangeEnd()->format('Y-m-d H:i:s'));
    }

    /**
     * Provide test data for date ranges
     *
     * @return array
     */
    public function timezonesProvider()
    {
        $identifiers = \DateTimeZone::listIdentifiers();

        $date_ranges = [
            ['2017-08-01 00:00:00', '2017-08-30 23:59:59'],
            ['2017-01-01 00:00:00', '2017-12-31 23:59:59']
        ];
        $data = [];

        // Provide a date range for every identifier unique offset
        foreach ($date_ranges as $date_range) {
            foreach ($identifiers as $identifier) {
                $tz = new \DateTimeZone($identifier);
                $offset_1 = $tz->getOffset(new Carbon($date_range[0]));
                $offset_2 = $tz->getOffset(new Carbon($date_range[1]));
                $data[$offset_1] = [
                    $identifier,
                    $date_range[0],
                    $date_range[1],
                ];
                $data[$offset_2] = [
                    $identifier,
                    $date_range[0],
                    $date_range[1],
                ];
            }
        }
        return $data;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function filterExceptionsDataProvider()
    {
        return [
                [
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2020-01-01 00:00:00',
                    ],
                    'months' => [
                        'jan' => true,
                    ]
                ],
                [
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2020-01-01 00:00:00',
                    ],
                    'months' => [
                        'jan' => true,
                        'feb' => true,
                    ]
                ],
                [
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2020-01-01 00:00:00',
                    ],
                    'months' => [
                        'jan' => true,
                        'feb' => true,
                        'dec' => true,
                    ]
                ],
                [
                    'range' => [
                        '2017-01-01 00:00:00',
                        '2020-01-01 00:00:00',
                    ],
                    'days' => [
                        'sat' => true,
                        'sun' => true,
                    ],
                    'months' => [
                        'jan' => true,
                        'feb' => true,
                        'dec' => true,
                    ]
                ],
        ];
    }

    /**
     * Provide test data for testGetDefinitionMonths()
     *
     * @return array
     */
    public function definitionMonthsProvider()
    {
        return [
            [
                [],
                [
                    'jan' => true,
                    'feb' => true,
                    'mar' => true,
                    'apr' => true,
                    'may' => true,
                    'jun' => true,
                    'jul' => true,
                    'aug' => true,
                    'sep' => true,
                    'oct' => true,
                    'nov' => true,
                    'dec' => true,
                ]
            ],
            [
                [
                    'months' => [
                        'jan' => true,
                    ]
                ],
                [
                    'jan' => true,
                    'feb' => false,
                    'mar' => false,
                    'apr' => false,
                    'may' => false,
                    'jun' => false,
                    'jul' => false,
                    'aug' => false,
                    'sep' => false,
                    'oct' => false,
                    'nov' => false,
                    'dec' => false,
                ]
            ],
        ];
    }

    /**
     * Provide test data for testGetDefinitionDays()
     *
     * @return array
     */
    public function definitionDaysProvider()
    {
        return [
            [
                [],
                [
                    'mon' => true,
                    'tue' => true,
                    'wed' => true,
                    'thu' => true,
                    'fri' => true,
                    'sat' => true,
                    'sun' => true,
                ]
            ],
            [
                [
                    'days' => [
                        'mon' => true
                    ],
                ],
                [
                    'mon' => true,
                    'tue' => false,
                    'wed' => false,
                    'thu' => false,
                    'fri' => false,
                    'sat' => false,
                    'sun' => false,
                ]
            ],
            [
                [
                    'days' => [
                        'mon' => true,
                        'tue' => true,
                    ],
                ],
                [
                    'mon' => true,
                    'tue' => true,
                    'wed' => false,
                    'thu' => false,
                    'fri' => false,
                    'sat' => false,
                    'sun' => false,
                ]
            ],
            [
                [
                    'days' => [
                        'mon' => 'mon',
                        'tue' => 1,
                    ],
                ],
                [
                    'mon' => true,
                    'tue' => true,
                    'wed' => false,
                    'thu' => false,
                    'fri' => false,
                    'sat' => false,
                    'sun' => false,
                ]
            ],
            [
                [
                    'days' => [
                        'mon' => 'invalid_day',
                        'tue' => 1,
                    ],
                ],
                [
                    'mon' => false,
                    'tue' => true,
                    'wed' => false,
                    'thu' => false,
                    'fri' => false,
                    'sat' => false,
                    'sun' => false,
                ]
            ],
        ];
    }
}
