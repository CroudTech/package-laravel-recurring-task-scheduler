<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Weeks;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class LastWorkingDay extends Base implements ScheduleParserContract
{
    /**
     * Return generated dates from provided schedule definition
     *
     * @return array
     */
    public function getDates() : array
    {
        if (empty($this->generated)) {
            $interval = $this->getInterval();
            $current_date = $this->getStartDate();

            $iteration_count = 0;
            while ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                $last_working_day = $current_date->copy()->timezone($this->getTimezone())->startOfWeek()->next(Carbon::FRIDAY)->setTime(...explode(':', $this->getTimeOfDay()));
                if ($last_working_day->between($this->getRangeStart(), $this->getRangeEnd())) {
                    $this->generated[] = $last_working_day->copy();
                }
                $current_date->addWeeks($interval)->setTime(...explode(':', $this->getTimeOfDay()));
                $iteration_count++;
            }
        }

        $this->generated = $this->filterExceptions($this->generated);
        $this->sortDates();
        $this->fixTimezones();
        return $this->generated;
    }
}
