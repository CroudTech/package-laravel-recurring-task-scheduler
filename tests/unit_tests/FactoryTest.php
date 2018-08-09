<?php
namespace CroudTech\RecurringTaskScheduler\Tests;

use Carbon\Carbon;

class FactoryTest extends BrowserKitTestCase
{
    /**
     * Check that the correct exception is thrown when no definition type is provided
     *
     * @expectedException \CroudTech\RecurringTaskScheduler\Exceptions\InvalidArgument
     * @return void
     */
    public function testProviderErrorHandlingWithNoDefinitionType()
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory([]);
    }

    /**
     * Check that the correct exception is thrown when invalid definition type is provided
     *
     * @expectedException \CroudTech\RecurringTaskScheduler\Exceptions\InvalidArgument
     * @return void
     */
    public function testProviderErrorHandlingWithInvalidType()
    {
        $parser = $this->app->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory(['type' => 'Invalid Type']);
    }
}
