<?php
namespace CroudTech\RecurringTaskScheduler\Traits;

use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ScheduleableTrait
{
    /**
     * Get the schedule relationship query
     *
     * @return MorphMany
     */
    public function schedule() : MorphMany
    {
        return $this->morphMany(Schedule::class, 'scheduleable');
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
