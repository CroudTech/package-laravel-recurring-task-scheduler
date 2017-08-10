<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

abstract class Base
{
    /**
     * The schedule definition to parse
     *
     * @var array
     */
    protected $definition;

    public function __construct(array $definition)
    {
        $this->definition = $definition;
    }
}
