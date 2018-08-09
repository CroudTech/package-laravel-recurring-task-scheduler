<?php
namespace CroudTech\RecurringTaskScheduler\Tests;
class ExampleTest extends BrowserKitTestCase
{
    protected function setUp()
    {
        parent::setUp();

        \Artisan::call('vendor:publish',
            [
                '--provider' => 'CroudTech\RecurringTaskScheduler\RecurringTaskSchedulerServiceProvider',
                '--tag' => 'migrations'
            ]
        );

        \Artisan::call('migrate');
    }

    protected function tearDown()
    {
        \Artisan::call('migrate:reset');

        parent::tearDown();
    }

    public function testExample()
    {
        $this->assertTrue(true);
    }
}