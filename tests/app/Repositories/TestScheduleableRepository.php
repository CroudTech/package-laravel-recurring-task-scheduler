<?php
namespace CroudTech\RecurringTaskScheduler\Tests\App\Repositories;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableRepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable;
use CroudTech\Repositories\BaseRepository as CroudTechBaseRepository;
use CroudTech\Repositories\Contracts\RepositoryContract;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;

class TestScheduleableRepository extends CroudTechBaseRepository implements ScheduleableRepositoryContract
{
    /**
     * Return the model name for this repository
     *
     * @method getModelName
     * @return string
     */
    public function getModelName() : string
    {
        return TestScheduleable::class;
    }
}
