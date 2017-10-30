<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;

class Years extends Base implements ScheduleParserContract
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

            while ($current_date->lte($this->getRangeEnd()) && count($this->generated) < 500 && $iteration_count < 1000) {
                if ($current_date->between($this->getRangeStart(), $this->getRangeEnd()) &&
                    $current_date->format('j') == $day_number
                ) {
                    $this->generated[] = $current_date->copy();
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
