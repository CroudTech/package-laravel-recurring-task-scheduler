<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;

class ScheduleRepository implements RepositoryContract {

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