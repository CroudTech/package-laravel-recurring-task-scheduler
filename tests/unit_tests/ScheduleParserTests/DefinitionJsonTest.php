<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ScheduleParserTest;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;

/**
 * Test scheduler handles input from json string as passed in by the front-end
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
        $generated_dates_array = $generated_dates->map(
            function ($date) {
                return $date->format('c');
            }
        )->toArray();
        $this->assertEquals($expected, $generated_dates_array);
    }

    /**
     * Load json definitions from data file
     *
     * @return array
     */
    public function definitionsProvider() : array
    {
        return include $this->test_root . '/test_data/json_definitions.php';
    }
}
