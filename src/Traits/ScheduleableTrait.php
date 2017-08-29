<?php
namespace CroudTech\RecurringTaskScheduler\Traits;

use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait ScheduleableTrait
{
    /**
     * Get the schedule relationship query
     *
     * @return MorphOne
     */
    public function schedule() : MorphOne
    {
        return $this->morphOne(Schedule::class, 'scheduleable');
    }

    /**
     * Schedule callback
     *
     * @param Schedule $schedule
     * @param ScheduleEvent $event
     * @return boolean
     */
    abstract public function scheduleEventTrigger(ScheduleEventTriggerEvent $event) : bool;
}
