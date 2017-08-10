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
            while ($current_date < $this->getRangeEnd()) {
                $this->generated[] = $current_date->copy();
                $modify_method = sprintf('add%s', ucfirst(camel_case($this->definition['period'])));
                $current_date->$modify_method($interval)->setTime(...explode(':', $this->getTimeOfDay()));
            }
        }

        return $this->generated;
    }
}

