<?php
namespace CroudTech\RecurringTaskScheduler\Observers;

use CroudTech\RecurringTaskScheduler\Events\ScheduleEventDeleteEvent;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\RecurringTaskScheduler\Repository\ScheduleEventRepository;

class ScheduleEventObserver
{
    /**
     * The ScheduleEventRepository
     *
     * @var ScheduleEventRepository
     */
    protected $respository;

    /**
     * Constructor
     *
     * @param ScheduleEventRepository $respository
     */
    public function __construct(ScheduleEventRepository $respository)
    {
        $this->respository = $respository;
    }

    /**
     * Get the ScheduleEvent respository
     *
     * @return ScheduleRepository
     */
    public function getRepository() : ScheduleRepository
    {
        return $this->respository;
    }

    /**
     * Listen to the ScheduleEvent saved event.
     *
     * @return void
     */
    public function saving(ScheduleEvent $schedule_event)
    {
        if (is_null($schedule_event['original_date'])) {
            $schedule_event['original_date'] = $schedule_event['date'];
        }

        return true;
    }

    /**
     * Listen to the ScheduleEvent deleted event.
     *
     * @return void
     */
    public function deleted(ScheduleEvent $schedule_event)
    {
        event(new ScheduleEventDeleteEvent($schedule_event));
    }
}
