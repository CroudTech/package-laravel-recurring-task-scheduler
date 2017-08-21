<?php
namespace CroudTech\RecurringTaskScheduler\Events;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Queue\SerializesModels;

class ScheduleEventTriggerEvent
{
    use SerializesModels;

    /**
     * The Schedule event being tiggered
     *
     * @var ScheduleEvent
     */
    public $schedule_event;

    /**
     * Create a new event instance.
     *
     * @param  ScheduleEvent  $schedule_event
     * @return void
     */
    public function __construct(ScheduleEvent $schedule_event)
    {
        $this->schedule_event = $schedule_event;
    }
}
