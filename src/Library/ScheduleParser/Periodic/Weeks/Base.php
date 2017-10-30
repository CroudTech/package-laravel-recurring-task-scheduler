<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Weeks;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base as PeriodicBase ;

class Base extends PeriodicBase
{
    /**
     * Return generated dates from provided schedule definition
     *
     * @return Collection
     */
    public function filterExceptions($generated)
    {
        return collect($generated)->filter(function ($date) {
            $month = strtolower($date->format('M'));
            return
                array_key_exists($month, $this->definition['months']) &&
                $this->definition['months'][$month] === true;
        })->values()->toArray();
    }
}