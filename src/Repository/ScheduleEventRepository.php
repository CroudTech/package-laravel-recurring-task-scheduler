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
     * @param Collection | QueryBuilder $this->query()
     * @param $request
     * @return void
     */
    protected function modifyApiPaginateQuery(Request $request)
    {
        if (empty($request['all_events'])) {
            $this->query()->futureEvents();
        }

        unset($request['all_events']);

        parent::modifyApiPaginateQuery($request);
    }
}
