<?php
namespace CroudTech\RecurringTaskScheduler\Events;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent as ScheduleEventModel;
use Illuminate\Queue\SerializesModels;

class ScheduleEventTriggerEvent
{
    use SerializesModels;

    /**
     * The Schedule event being tiggered
     *
     * @var ScheduleEventModel
     */
    public $schedule_event;

    /**
     * Create a new event instance.
     *
     * @param  ScheduleEvent  $schedule_event
     * @return void
     */
    public function __construct(ScheduleEventModel $schedule_event)
    {
        $this->schedule_event = $schedule_event;
    }
}
