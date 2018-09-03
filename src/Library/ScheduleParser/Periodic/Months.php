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
            $day_number = $this->getDayNumber();
            $current_date = $this->getStartDate()->day($day_number);

            $iteration_count = 0;

            while ($current_date->lte($this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                $month = $current_date->month;
                $year = $current_date->year;

                if ($current_date->format('j') == $day_number) {
                    if ($month <= 12) {
                        $month = $month + $interval;
                    }
                    if ($month > 12) {
                        $month = $month - 12;
                        $year = $year + 1;
                    }
                }

                if (isset($this->definition['week_number']) && !empty($this->definition['week_number'])) {
                    foreach ($this->getDayNames() as $day_name) {
                        $modification_string = sprintf('%s %s of %s %s', $this->getWeekNumberAsString(), $this->formatShortDay($day_name, 'l'), $current_date->format('F'), $current_date->year);
                        $current_date->modify($modification_string)->setTime(...explode(':', $this->getTimeOfDay()));
                        if ($current_date->lte($this->getRangeEnd()) && $current_date->gte($this->getRangeStart())) {
                                $this->generated[] = $current_date->copy();
                        }
                    }
                } else {
                    if ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) &&
                        $current_date->format('j') == $day_number
                    ) {
                            $this->generated[] = $current_date->copy();
                    }
                }

                $current_date->month($month)->year($year);
                while ($current_date->day != $day_number) {
                    $current_date->day($day_number);
                }

                $iteration_count++;
            }
        }

        $raw = $this->generated;

        $this->generated = $this->filterExceptions($this->generated);

        // dd($raw, '---', $this->generated, '---------', $this->getRangeStart(), $this->getRangeEnd());
        // dd($raw, '---', $this->generated);

        $this->sortDates();
        $this->fixTimezones();
        return $this->generated;
    }
}
