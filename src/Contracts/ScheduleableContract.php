<?php
namespace CroudTech\RecurringTaskScheduler\Contracts;

use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;

interface ScheduleableContract
{
    /**
     * Schedule callback
     *
     * @return boolean
     */
    public function scheduleEventTrigger(ScheduleEventTriggerEvent $event) : bool;
}
