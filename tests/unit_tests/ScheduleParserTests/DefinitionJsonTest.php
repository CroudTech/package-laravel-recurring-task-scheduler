<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

require_once __DIR__ . '/Base.php';

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

/**
 * Test scheduler handles input from json string as passed in by the front-end
 */
class DefinitionJsonTest extends Base
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

    /**
     * Load json definitions from data file
     *
     * @return array
     */
    public function definitionsProvider() : array
    {
        return include $this->test_root . '/test_data/integration_test_json_definitions.php';
    }
}
