<?php
namespace CroudTech\RecurringTaskScheduler\Library;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class ScheduleParser implements ScheduleParserContract
{
    public function getDatesFromDefinition(array $definition) : array
    {
        return [];
    }
}
