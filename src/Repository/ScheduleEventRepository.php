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

     /**
     * modifyApiPaginateQueryRepository
     * @method modifyApiPaginateQueryRepository
     * @param  array    $query_params [description]
     * @return void
     */
    public function modifyApiPaginateQueryRepository($query_params) {
        if (empty($query_params['all_events'])) {
            $this->query()->futureEvents();
        }
        
        unset($query_params['all_events']); 

        parent::modifyApiPaginateQueryRepository($query_params);
    }
}
