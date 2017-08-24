<?php
namespace CroudTech\RecurringTaskScheduler\Contracts;

use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;

interface ScheduleableContract
{
    /**
     * Schedule callback
     *
     * @return bool
     */
    public function scheduleEventTrigger(ScheduleEventTriggerEvent $event) : bool;
}
