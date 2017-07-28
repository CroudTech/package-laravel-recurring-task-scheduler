<?php
namespace CroudTech\RecurringTaskScheduler\Library;

use CroudTech\RecurringTaskScheduler\Contracts\SchedulePeriodParserContract;

class SchedulePeriodParser implements SchedulePeriodParserContract
{
    public function getDatesFromDefinition(array $definition) : array
    {
        return [];
    }
}
