<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic\Months;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;

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
            $day_number = $this->definition['day_number'] ? $this->definition['day_number'] : $this->getRangeStart()->format('j');
            $current_date = $this->getRangeStart()
                ->timezone($this->getTimezone())
                ->setTime(...explode(':', $this->getTimeOfDay()));

            $iteration_count = 0;

            while ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                $start_of_month = $current_date->copy()->startOfMonth()->setTime(...explode(':', $this->getTimeOfDay()));
                if ($start_of_month->isWeekend()) {
                    $start_of_month->modify('next weekday')->setTime(...explode(':', $this->getTimeOfDay()));
                }
                if ($start_of_month->between($this->getRangeStart(), $this->getRangeEnd())) {
                    $this->generated[] = $start_of_month->copy();
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
