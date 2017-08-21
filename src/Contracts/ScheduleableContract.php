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
    public function trigger(ScheduleEventTriggerEvent $event) : bool;
}
