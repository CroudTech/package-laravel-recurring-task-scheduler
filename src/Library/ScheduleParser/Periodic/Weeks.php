<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;

class Weeks extends Base implements ScheduleParserContract
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
            $current_date = $this->getRangeStart()->setTime(...explode(':', $this->getTimeOfDay()));

            $days = collect($this->definition['days'])->filter()->keys()->map(function ($val) {
                return ucfirst($val);
            });
            $day_of_week = 0;
            // Prevent iteration over more than 500 days to stop incorrect definition from causing infinite loops
            $iteration_count = 0;
            while ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                $start_of_week = $current_date->copy()->timezone($this->getTimezone())->startOfWeek()->setTime(...explode(':', $this->getTimeOfDay()));
                $day_of_week = $start_of_week->copy();
                $day_number = 0;

                while ($day_number < 7) {
                    if ($day_of_week->between($this->getRangeStart(), $this->getRangeEnd()) && $days->contains($day_of_week->format('D'))) {
                        $this->generated[] = $day_of_week->copy();
                    }

                    $day_of_week->addDays(1);
                    $day_number++;
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
