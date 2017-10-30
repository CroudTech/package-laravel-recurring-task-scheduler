<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Weeks;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class FirstWorkingDay extends Base implements ScheduleParserContract
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
            $current_date = $this->getRangeStart()
                ->timezone($this->getTimezone())
                ->startOfWeek()
                ->setTime(...explode(':', $this->getTimeOfDay()));
            $iteration_count = 0;
            while ($current_date->lte($this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                if ($current_date->between($this->getRangeStart(), $this->getRangeEnd())) {
                    $this->generated[] = $current_date->copy();
                } else {
                    \Log::debug($current_date->format('c'));
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
