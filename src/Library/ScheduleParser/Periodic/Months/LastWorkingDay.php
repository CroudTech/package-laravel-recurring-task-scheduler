<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Months;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;

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
            $day_number = $this->definition['day_number'] ? $this->definition['day_number'] : $this->getRangeStart()->format('j');
            $current_date = $this->getRangeStart()
                ->timezone($this->getTimezone())
                ->setTime(...explode(':', $this->getTimeOfDay()));

            $iteration_count = 0;

            while ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                $end_of_month = $current_date->copy()->endOfMonth()->setTime(...explode(':', $this->getTimeOfDay()));
                if ($end_of_month->isWeekend()) {
                    $end_of_month->modify('last weekday this month')->setTime(...explode(':', $this->getTimeOfDay()));
                }
                if ($end_of_month->between($this->getRangeStart(), $this->getRangeEnd())) {
                    $this->generated[] = $end_of_month->copy();
                }

                $current_date->month($current_date->month + $interval);
                $iteration_count++;
            }
        }

        $this->generated = $this->filterExceptions($this->generated);
        $this->sortDates();
        $this->fixTimezones();
        return $this->generated;
    }
}
