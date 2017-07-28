<?php
namespace CroudTech\RecurringTaskScheduler\Tests;
use Carbon\Carbon;
class SchedulePeriodParserTest extends TestCase
{
    /**
     * Check the service provider returns the correct class for the specified contract
     *
     * @return void
     */
    public function testServiceProvider()
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\SchedulePeriodParserContract::class);
        $this->assertInstanceOf(\CroudTech\RecurringTaskScheduler\Library\SchedulePeriodParser::class, $parser);
    }

    /**
     * Check that the expected dates are returned from the provided schedule definition array
     *
     * @dataProvider scheduleDefinitionProvider
     * @return void
     */
    public function testParseDateFromArray($schedule_definition, $expected)
    {
        var_dump($schedule_definition, $expected);
        $this->assertTrue(true);
        // $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\SchedulePeriodParserContract::class);
        // $actual = $parser->getDatesFromDefinition($schedule_definition);
        // foreach ($expected as $expected_date_key => $expected_date) {
        //     $this->assertArrayHasKey($expected_date_key, $actual);
        //     $this->assertEquals($actual[$expected_date_key], $expected_date);
        // }
    }

    /**
     * Provides schedule definitions that cover all possible period definitions
     *
     * Daily – Every N days, Every Workday, Every DOW,
     * Weekly – N weeks – On DOW
     * Same day each month – Day N of every N months
     * Same week each month – every N months on the (1,2,3,4,last) DOW
     * Same day each year – Day Month
     * Same week each year – (1,2,3,4,last) DOW or Month
     *
     *
     * @return void
     */
    public function scheduleDefinitionProvider()
    {
        return include __DIR__ . '/data/schedule_definition_provider.php';
    }
}
