<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;

class Months extends Base implements ScheduleParserContract
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
                ->setTime(...explode(':', $this->getTimeOfDay()))
                ->day($day_number);

            $iteration_count = 0;

            while ($current_date->lte($this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                $month = $current_date->month;
                $year = $current_date->year;

                if ($current_date->format('j') == $day_number) {
                    if ($month == 12) {
                        $month = 1;
                        $year = $year + 1;
                    } else {
                        $month = $month + $interval;
                    }
                }

                if ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) &&
                    $current_date->format('j') == $day_number
                ) {
                    $this->generated[] = $current_date->copy();
                }

                $current_date->month($month)->year($year);
                while ($current_date->day != $day_number) {
                    $current_date->day($day_number);
                }

                $iteration_count++;
            }
        }

        $this->generated = $this->filterExceptions($this->generated);
        $this->sortDates();
        $this->fixTimezones();
        return $this->generated;
    }
}
