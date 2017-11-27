<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;
use Illuminate\Support\Collection;

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
            $current_date = $this->getStartDate();

            $days = collect($this->definition['days'])->filter()->keys()->map(function ($val) {
                return ucfirst($val);
            });
            $day_of_week = 0;

            // Prevent iteration over more than 1000 days to stop incorrect definition from causing infinite loops
            $iteration_count = 0;
            foreach ($this->getWeeksForDateRange($this->getRangeStart(), $this->getRangeEnd(), $interval) as $current_week) {
                $day_of_week = $current_week->copy()->timezone($this->timezone)->setTime(...explode(':', $this->getTimeOfDay()));
                $day_number = 0;

                while ($day_number < 7) {
                    if ($day_of_week->between($this->getRangeStart(), $this->getRangeEnd()) && $days->contains($day_of_week->format('D'))) {
                        $this->generated[] = $day_of_week->copy();
                    }

                    $day_of_week->addDays(1);
                    $day_number++;
                    $iteration_count++;
                    if ($iteration_count > 1000) {
                        throw new \Exception('Recurring task scheduler cannot itterate over 1000 days for a single schedule');
                    }
                }
            }
        }

        $this->generated = $this->filterExceptions($this->generated);
        $this->sortDates();
        $this->fixTimezones();
        return $this->generated;
    }

    /**
     * Get start date of each week in date range
     *
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @param int $interval
     * @return Collection
     */
    public function getWeeksForDateRange(Carbon $start_date, Carbon $end_date, int $interval) : Collection
    {
        $first_date = $start_date->copy()->startOfWeek();
        $current_date = $first_date->copy();
        $last_date = $end_date->copy()->endOfWeek();
        $current_date->startOfWeek();
        $weeks = collect();

        while ($current_date->between($first_date, $last_date)) {
            $weeks[] = $current_date->copy();
            $current_date->addWeeks($interval);
        }

        return $weeks;
    }
}
