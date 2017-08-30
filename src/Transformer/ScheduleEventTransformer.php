<?php
namespace CroudTech\RecurringTaskScheduler\Transformer;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\Repositories\Contracts\TransformerContract;
use League\Fractal\TransformerAbstract;

class ScheduleEventTransformer extends TransformerAbstract implements TransformerContract
{
    /**
     * Transform the schedule event
     *
     * @return array
     */
    public function transform(ScheduleEvent $schedule_event) : array
    {
        return $schedule_event->toArray();
    }
}
