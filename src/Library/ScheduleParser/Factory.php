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

    public function factory($definition) : ScheduleParserContract
    {
        $make_method = version_compare($this->app->version(), '5.4', '<') ? 'make' : 'makeWith'; // Laravel 5.3/4 compatibility
        return $this->app->$make_method(ScheduleParserContract::class, ['definition' => $definition]);
    }
}
