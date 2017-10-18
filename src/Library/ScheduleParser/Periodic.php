<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class Periodic extends Base implements ScheduleParserContract
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
            if (isset($this->definition['day_of_month']) && is_numeric($this->definition['day_of_month'])) {
                $current_date->day($this->definition['day_of_month']);
            }

            // Prevent iteration over more than 1000 days to stop incorrect definition from causing infinite loops
            while ($current_date->lte($this->getRangeEnd()) && count($this->generated) < 1000) {
                if (isset($this->definition['week_of_month']) && !empty($this->definition['week_of_month'])) {
                    $this->definition['period'] = 'months';
                    foreach ($this->definition['days'] as $day => $day_enabled) {
                        if ($day_enabled) {
                            $modification_string = sprintf('%s %s of %s %s', ucfirst($this->definition['week_of_month']), $this->formatShortDay($day, 'l'), $current_date->format('F'), $current_date->year);
                            $current_date->modify($modification_string);
                            $current_date->setTime(...explode(':', $this->getTimeOfDay()));
                            if ($current_date->lte($this->getRangeEnd()) && $current_date->gte($this->getRangeStart())) {
                                $this->generated[] = $current_date->copy();
                            }
                        }
                    }
                } else {
                    if ($current_date->lte($this->getRangeEnd()) && $current_date->gte($this->getRangeStart())) {
                        $this->generated[] = $current_date->copy();
                    }
                }

                $modify_method = sprintf('add%s', ucfirst(camel_case($this->definition['period'])));
                $current_date->$modify_method($interval)->setTime(...explode(':', $this->getTimeOfDay()));
            }
        }
        $this->generated = collect($this->generated)->map(function ($date) {
            return $date->setTimezone('UTC');
        })->toArray();
        $this->generated = $this->filterExceptions($this->generated);
        $this->sortDates();
        return $this->generated;
    }
}
