<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\Repositories\BaseRepository;
use CroudTech\Repositories\Contracts\RepositoryContract;

class ScheduleRepository extends BaseRepository implements RepositoryContract, ScheduleRepositoryContract {

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
