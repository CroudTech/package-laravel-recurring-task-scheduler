<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;
use CroudTech\Repositories\Contracts\RepositoryContract;

class ScheduleEventRepository extends BaseRepository implements RepositoryContract, ScheduleEventRepositoryContract {

    /**
     * Return the model name for this repository
     *
     * @method getModelName
     * @return string
     */
    public function getModelName() : string
    {
        return ScheduleEvent::class;
    }
}
