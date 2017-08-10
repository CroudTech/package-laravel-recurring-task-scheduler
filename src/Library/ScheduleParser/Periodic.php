<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class Periodic extends Base implements ScheduleParserContract
{
    /**
     * Return generated dates from provided schedule definition
     *
     * @return Collection
     */
    public function getDates() : array
    {
        return [];
    }
}

