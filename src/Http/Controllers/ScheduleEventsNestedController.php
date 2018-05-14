<?php
namespace CroudTech\RecurringTaskScheduler\Http\Controllers;

use CroudTech\RecurringTaskScheduler\Http\Requests\ScheduleEventCreateFormRequest;
use CroudTech\RecurringTaskScheduler\Http\Requests\ScheduleEventUpdateFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;

class ScheduleEventsNestedController extends BaseResourceController
{
    /**
     * Index endpoint
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request, int $schedule_id)
    {
        $this->repository->clearQuery();
        $this->repository->query()->with('schedule')->where('schedule_id', $schedule_id);
        return $this->apiIndex($request);
    }

    /**
     * RESTful Show Method
     *
     * @param  Request $request Request
     * @param  int $id Id
     * @return string
     */
    public function show(Request $request, int $schedule_id, int $id, ScheduleRepositoryContract $schedule_repository)
    {
        if (($schedule = $schedule_repository->find($schedule_id)) && ($item = $schedule->scheduleEvents()->where('id', $id)->first())) {
            return $this->sendResponse($this->transform($item));
        }

        return $this->sendError($message = 'Not Found', $response_code = 404);
    }

    /**
     * RESTful Destroy Method
     *
     * @param  Request $request Request
     * @param  int $id Id
     * @return string
     */
    public function destroy(Request $request, int $schedule_id, int $id, ScheduleRepositoryContract $schedule_repository)
    {
        if (($schedule = $schedule_repository->find($schedule_id)) && ($item = $schedule->scheduleEvents()->where('id', $id)->first())) {
            $item->delete();
            return $this->sendSuccess(sprintf('%s::%s deleted', get_class($item), $id), $response_code = 200);
        }

        return $this->sendError('Not Found', $response_code = 404);

    }

    /**
     * RESTful Store Method
     *
     * @param  Request $request Request
     * @return string
     */
    public function store(ScheduleEventCreateFormRequest $request, int $schedule_id, ScheduleRepositoryContract $schedule_repository)
    {
        $request_data = $request->all();
        $request_data['schedule_id'] = $schedule_id;
        if ($item = $this->repository->create($request_data)) {
            $this->postStore($request, $item);
            return $this->sendResponse($this->transform($item->fresh()));
        }

        return $this->sendError('Not Found', $response_code = 404);
    }

    /**
     * RESTful Update method
     *
     * @param  Request $request Request
     * @param  int $id ID
     * @return string
     */
    public function update(ScheduleEventUpdateFormRequest $request, int $schedule_id, int $id, ScheduleRepositoryContract $schedule_repository)
    {
        if (($schedule = $schedule_repository->find($schedule_id)) && ($item = $schedule->scheduleEvents()->where('id', $id)->first())) {
            if ($this->repository->update($id, $request->all())) {
                $this->postUpdate($request, $item);
                $item = $item->fresh();
                return $this->sendResponse($this->transform($item->fresh()->load('schedule')));
            }

            return $this->sendError(sprintf('%s could not be saved', class_basename($this->repository->getModelName())), $response_code = 402);
        }

        return $this->sendError('Not Found', $response_code = 404);
    }
}
