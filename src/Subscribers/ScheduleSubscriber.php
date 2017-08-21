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
        if ($event->schedule_event->schedule->scheduleable->trigger($event) == true) {
            $event->schedule_event->trigger_success = true;
        }

        $event->schedule_event->save();
    }

    /**
     * Deal with schedule updates. When a schedule fundamentally changes then it's events need re-generating
     *
     * @return void
     */
    public function onScheduleUpdate($event)
    {

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

        $events->listen(
            ScheduleUpdateEvent::class,
            self::class . '@onScheduleUpdate'
        );
    }
}