<?php
namespace CroudTech\RecurringTaskScheduler\Events;

use CroudTech\RecurringTaskScheduler\Model\Schedule;
use Illuminate\Queue\SerializesModels;

class ScheduleUpdateEvent
{
    use SerializesModels;

    /**
     * The Schedule event being tiggered
     *
     * @var Schedule
     */
    public $schedule;

    /**
     * Create a new event instance.
     *
     * @param  Schedule  $schedule_event
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }
}
