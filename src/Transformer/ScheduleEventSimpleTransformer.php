<?php
namespace CroudTech\RecurringTaskScheduler\Transformer;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\Repositories\Contracts\TransformerContract;
use League\Fractal\TransformerAbstract;

class ScheduleEventSimpleTransformer extends TransformerAbstract implements TransformerContract
{
    /**
     * Transform the schedule event
     *
     * @return array
     */
    public function transform(ScheduleEvent $schedule_event) : array
    {
        return [
            'id' => $schedule_event['id'],
            'date' => $schedule_event['date'],
            'triggered_at' => $schedule_event['triggered_at'],
            'trigger_success' => $schedule_event['trigger_success'],
            'modified' => $schedule_event['modified'],
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => route('schedule.schedule-event.show', [
                        'schedule' => $schedule_event->schedule_id,
                        'schedule_event' => $schedule_event->id,
                    ]),
                ]
            ]
        ];
    }
}
