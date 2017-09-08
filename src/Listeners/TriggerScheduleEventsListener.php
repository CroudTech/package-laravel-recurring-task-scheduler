<?php
namespace CroudTech\RecurringTaskScheduler\Listeners;

use CroudTech\RecurringTaskScheduler\Events\ScheduleExecuteEvent;
use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;

class TriggerScheduleEventsListener
{
    /**
     * The Schedule Event Repository
     *
     * @var ScheduleEventRepositoryContract
     */
    protected $schedule_event_repository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ScheduleEventRepositoryContract $schedule_event_repository)
    {
        $this->schedule_event_repository = $schedule_event_repository;
    }

    /**
     * Handle the event.
     *
     * @param  ScheduleExecuteEvent  $event
     * @return void
     */
    public function handle(ScheduleExecuteEvent $event)
    {
        $events = $this->schedule_event_repository->getEventsForTimestamp($event->timestamp);
        foreach ($events as $schedule_event) {
            event(new ScheduleEventTriggerEvent($schedule_event, $event));
        }

        return true;
    }
}