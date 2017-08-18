<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Repository\Contracts\ScheduleEventRepositoryContract;
use CroudTech\Repositories\BaseRepository;
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
        return Schedule::class;
    }
}
