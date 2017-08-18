<?php
namespace CroudTech\RecurringTaskScheduler\Traits;

use CroudTech\RecurringTaskScheduler\Model\Schedule;
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
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}
