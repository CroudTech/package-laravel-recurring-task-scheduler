<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Years;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Months\LastDay as ParentClass;

class LastDay extends ParentClass implements ScheduleParserContract
{
    /**
     * Return generated dates from provided schedule definition
     *
     * @return array
     */
    public function getDates() : array
    {
        return parent::getDates();
    }
}
