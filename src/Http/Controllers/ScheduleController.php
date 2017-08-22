<?php
namespace CroudTech\RecurringTaskScheduler\Http\Controllers;

use CroudTech\RecurringTaskScheduler\Http\Requests\ScheduleFormRequest;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ScheduleController extends BaseController

{
    /**
     * RESTful Store Method
     *
     * @param  Request $request Request
     * @return string
     */
    public function store(ScheduleFormRequest $request)
    {
        $scheduleable_type = $request['scheduleable_type'];
        $scheduleable = $request['scheduleable_type']::findOrFail($request['scheduleable_id']);
        if ($item = $this->repository->createFromScheduleDefinition($request->all(), $scheduleable)) {
            $this->postStore($request, $item);
            return $this->sendResponse($this->transform($item));
        }

        throw new ModelNotFoundException(sprintf('%s not found', class_basename($this->repository->getModelName())));
    }
}
