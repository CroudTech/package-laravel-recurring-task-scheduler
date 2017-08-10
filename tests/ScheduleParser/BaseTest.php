<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParser;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

class BaseTest extends TestCase
{
    /**
     * Check that the parser gives the correct value for time_of_day
     *
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
     *
     */
    public function testGetInterval()
    {
        $parser = new PeriodicParser([]);
        $this->assertEquals(1, $parser->getInterval());
        $parser = new PeriodicParser(['interval' => '5']);
        $this->assertEquals(5, $parser->getInterval());
    }

    /**
     * Test daily periodic definition
     *
     * @group DEV1
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
        foreach ($date_ranges as $date_range) {
            foreach ($identifiers as $identifier) {
                $data[] = [
                    $identifier,
                    $date_range[0],
                    $date_range[1],
                ];
            }
        }
        return $data;
    }
}
