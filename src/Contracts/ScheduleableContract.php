<?php
namespace CroudTech\RecurringTaskScheduler\Contracts;

interface ScheduleableContract
{
    /**
     * Schedule callback
     *
     * @return bool
     */
    public function trigger() : bool;
}
