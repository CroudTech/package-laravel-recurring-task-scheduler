<?php
namespace CroudTech\RecurringTaskScheduler\Tests;

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
}
