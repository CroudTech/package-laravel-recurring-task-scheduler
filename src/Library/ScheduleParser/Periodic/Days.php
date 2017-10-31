<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Base;

class Days extends Base implements ScheduleParserContract
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
            $original_current_date = $current_date->copy();

            // Prevent iteration over more than 1000 days to stop incorrect definition from causing infinite loops
            while ($current_date->lte($this->getRangeEnd()) && count($this->generated) < 1000) {
                if (isset($this->definition['day_number'])) {
                    if (is_numeric($this->definition['day_number'])) {
                        $current_date->day($this->definition['day_number']);
                    } elseif (is_string($this->definition['day_number'])) {
                        $modification_string = sprintf('%s day of %s %s', ucfirst($this->definition['day_number']), $current_date->format('F'), $current_date->year);
                        $current_date->endOfMonth();
                        $current_date->setTime(...explode(':', $this->getTimeOfDay()));
                    }
                }
                if (isset($this->definition['week_number']) && !empty($this->definition['week_number'])) {
                    $this->definition['period'] = 'months';
                    foreach ($this->definition['days'] as $day => $day_enabled) {
                        if ($day_enabled) {
                            $modification_string = sprintf('%s %s of %s %s', ucfirst($this->definition['week_number']), $this->formatShortDay($day, 'l'), $current_date->format('F'), $current_date->year);
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
                switch ($this->definition['period']) {
                    case 'months':
                        $current_date->day($original_current_date->day);
                        break;
                }

                $modify_method = sprintf('add%s', ucfirst(camel_case($this->definition['period'])));
                $current_date->$modify_method($interval)->setTime(...explode(':', $this->getTimeOfDay()));
            }
        }

        $this->generated = $this->filterExceptions($this->generated);
        $this->sortDates();
        $this->fixTimezones();
        return $this->generated;
    }
}
