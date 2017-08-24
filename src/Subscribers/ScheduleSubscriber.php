<?php
namespace CroudTech\RecurringTaskScheduler\Subscribers;

use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;
use Carbon\Carbon;

class ScheduleSubscriber
{
    /**
     * When a schedule event is triggered we need to run the callback on the scheduleable object
     *
     * @return void
     */
    public function onScheduleEventTrigger(ScheduleEventTriggerEvent $event)
    {
        $event->schedule_event->trigger_success = false;
        $event->schedule_event->triggered_at = Carbon::now();
        if ($event->schedule_event->schedule->scheduleable->scheduleEventTrigger($event) == true) {
            $event->schedule_event->trigger_success = true;
        }

        $event->schedule_event->save();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ScheduleEventTriggerEvent::class,
            self::class . '@onScheduleEventTrigger'
        );
    }
}