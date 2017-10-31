<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

require_once __DIR__ . '/Base.php';

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;


class DefinitionsMonthlyTest extends Base
{
    /**
     * We're extending the parent so the phpunit output reports the correct test class in it's output
     *
     * @dataProvider definitionsProvider
     */
    public function testDefinitions($definition, $expected)
    {
        return parent::testDefinitions($definition, $expected);
    }

    public function definitionsProvider()
    {
        return include $this->test_root . '/test_data/schedule_definitions_monthly.php';
    }
}
