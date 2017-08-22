<?php
namespace CroudTech\RecurringTaskScheduler\Tests\RepositoryTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleTransformer;

class ScheduleTransformerTest extends TestCase
{
    /**
     * Create schedule from definition
     *
     * @dataProvider definitionsProvider
     * @group DEV
     */
    public function testTransformScheduleToDefinition($definition, $expected)
    {
        $transformer = $this->app->make(ScheduleTransformer::class);
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory(json_decode($definition, true));
        $parsed_definition = $parser->getDefinition();
        $attributes = $transformer->transformScheduleToDefinition($parsed_definition);

        $this->assertInternalType('array', $attributes);

        $expected_attributes = [
            "timezone",
            "type",
            "range_start",
            "range_end",
            "time_of_day",
            "interval",
            "period",
            "day_of_month",
            "week_of_month",
            "mon",
            "tue",
            "wed",
            "thu",
            "fri",
            "sat",
            "sun",
            "jan",
            "feb",
            "mar",
            "apr",
            "may",
            "jun",
            "jul",
            "aug",
            "sep",
            "nov",
            "dec",
        ];

        foreach ($expected_attributes as $expected_attribute) {
            $this->assertArrayHasKey($expected_attribute, $attributes);
            switch ($expected_attribute) {
                case 'mon':
                case 'tue':
                case 'wed':
                case 'thu':
                case 'fri':
                case 'sat':
                case 'sun':
                    $this->assertEquals($parsed_definition['days'][$expected_attribute], $attributes[$expected_attribute]);
                    break;
                case 'jan':
                case 'feb':
                case 'mar':
                case 'apr':
                case 'may':
                case 'jun':
                case 'jul':
                case 'aug':
                case 'sep':
                case 'oct':
                case 'nov':
                case 'dec':
                    $this->assertEquals($parsed_definition['months'][$expected_attribute], $attributes[$expected_attribute]);
                    break;
                case 'range_start':
                    $this->assertEquals($parsed_definition['range']['start'], $attributes[$expected_attribute]);
                    break;
                case 'range_end':
                    $this->assertEquals($parsed_definition['range']['end'], $attributes[$expected_attribute]);
                    break;
                default:
                    $this->assertEquals($parsed_definition[$expected_attribute], $attributes[$expected_attribute]);
                    break;
            }
        }
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
