<?php
namespace CroudTech\RecurringTaskScheduler\Tests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Weeks as WeeksParser;
use Mockery as m;

class PeriodicWeeklyTest extends BrowserKitTestCase
{
    /**
     * Make sure we get the correct range of weeks
     *
     * @return void
     */
    public function testGetWeeksForDateRange()
    {
        $mock = \Mockery::mock(WeeksParser::class . '[]', [[]]);
        $start_date = Carbon::parse('2017-11-01');
        $end_date = Carbon::parse('2018-01-01');
        $dates = $mock->getWeeksForDateRange($start_date, $end_date, 1)
            ->map(
                function ($var) {
                    return $var->format('c');
                }
            )
            ->toArray();
        $this->assertEquals(
            [
            '2017-10-30T00:00:00+00:00',
            '2017-11-06T00:00:00+00:00',
            '2017-11-13T00:00:00+00:00',
            '2017-11-20T00:00:00+00:00',
            '2017-11-27T00:00:00+00:00',
            '2017-12-04T00:00:00+00:00',
            '2017-12-11T00:00:00+00:00',
            '2017-12-18T00:00:00+00:00',
            '2017-12-25T00:00:00+00:00',
            '2018-01-01T00:00:00+00:00',
            ],
            $dates
        );
    }
}
