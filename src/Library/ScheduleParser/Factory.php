<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class Factory
{
    protected $app;

    /**
     * Inject application
     *
     * @param [type] $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * We're relying on the provider to get the actual classname and only using the factory to help with the laravel version compatibility
     * (laravel 5.4 removes the ability to inject extra variables from the make() method and uses the makeWith() method instead)
     *
     * When laravel 5.3 is no longer supported we can remove the factory and just rely on the makeWith() method from the DI container
     *
     * @param array $definition
     * @return ScheduleParserContract
     */
    public function factory($definition) : ScheduleParserContract
    {
        $make_method = version_compare($this->app->version(), '5.4', '<') ? 'make' : 'makeWith'; // Laravel 5.3/4 compatibility
        return $this->app->$make_method(ScheduleParserContract::class, ['definition' => $definition]);
    }
}
