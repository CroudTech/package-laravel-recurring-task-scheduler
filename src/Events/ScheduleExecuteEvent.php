<?php
namespace CroudTech\RecurringTaskScheduler\Events;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Queue\SerializesModels;

class ScheduleExecuteEvent
{
    public $timestamp;

    /**
     * Create a new event instance.
     *
     * @param  ScheduleEvent  $schedule_event
     * @return void
     */
    public function __construct()
    {
        $this->timestamp = Carbon::now();
    }
}
