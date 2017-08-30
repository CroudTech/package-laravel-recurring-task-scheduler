<?php
namespace CroudTech\RecurringTaskScheduler\Http\Controllers;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableRepositoryContract;
use CroudTech\RecurringTaskScheduler\Http\Requests\ScheduleCreateFormRequest;
use CroudTech\RecurringTaskScheduler\Http\Requests\ScheduleUpdateFormRequest;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\Repositories\Fractal;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class NestedScheduleController extends BaseController
{
    /**
     * The repository for the scheduleable item
     *
     * @var ScheduleableRepositoryContract
     */
    protected $scheduleable_repository;

    /**
     * Construct Method
     *
     * @param Request                       $name The current request
     * @param \Illuminate\Auth\AuthManager  $auth_manager The auth manager
     * @param RepositoryContract            $repository
     */
    public function __construct(Request $request, AuthManager $auth_manager, RepositoryContract $repository = null, ScheduleableRepositoryContract $scheduleable_repository)
    {
        $this->repository = $repository;
        $this->fractal = new Fractal($request);
        $this->scheduleable_repository = $scheduleable_repository;
    }

    /**
     * RESTful Show Method
     *
     * @param  Request $request Request
     * @return string
     */
    public function index(Request $request, $scheduleable_id)
    {
        $this->repository->query()
            ->where('scheduleable_id', $scheduleable_id)
            ->where('scheduleable_type', $this->scheduleable_repository->getModelName());

        return $this->apiIndex($request);
    }

    /**
     * RESTful Show Method
     *
     * @param  Request $request Request
     * @param  int $id Id
     * @return string
     */
    public function show(Request $request, $scheduleable_id, $id)
    {
        if ($item = $this->repository->query()
                ->where('id', $id)
                ->where('scheduleable_id', $scheduleable_id)
                ->where('scheduleable_type', $this->scheduleable_repository->getModelName())->first()) {
            return $this->sendResponse($this->transform($item));
        }

        throw new ModelNotFoundException(sprintf('%s not found', class_basename($this->repository->getModelName())));
    }

    /**
     * RESTful destroy method
     *
     * @param Request $request Request
     * @param int $id Id
     * @return string
     */
    public function destroy(Request $request, $scheduleable_id, $id)
    {
        $query = $this->repository->query()
            ->where('id', $id)
            ->where('scheduleable_id', $scheduleable_id)
            ->where('scheduleable_type', $this->scheduleable_repository->getModelName());

        $query->firstOrFail()->delete();
        \Log::debug('Deleted');
        return $this->sendSuccess(sprintf('%s deleted', class_basename($this->repository->getModelName())));
    }

    /**
     * RESTful Store Method
     *
     * @param  Request $request Request
     * @return string
     */
    public function store(ScheduleCreateFormRequest $request, $scheduleable_id)
    {
        $scheduleable_type = $request['scheduleable_type'];
        $scheduleable = $request['scheduleable_type']::findOrFail($request['scheduleable_id']);
        if ($item = $this->repository->createFromScheduleDefinition($request->all(), $scheduleable)) {
            $this->postStore($request, $item);
            return $this->sendResponse($this->transform($item));
        }

        throw new ModelNotFoundException(sprintf('%s not found', class_basename($this->repository->getModelName())));
    }

    /**
     * RESTful Update method
     *
     * @param  Request $request Request
     * @param  int $id ID
     * @return string
     */
    public function update(ScheduleUpdateFormRequest $request, $scheduleable_id, $id)
    {
        if (($item = $this->repository->find($id))) {
            if ($this->repository->updateFromScheduleDefinition($id, $request->all())) {
                $this->postUpdate($request, $item);
                $item = $item->fresh();
                return $this->sendResponse($this->transform($item->fresh()));
            }

            throw new ApiException(sprintf('%s could not be saved', class_basename($this->repository->getModelName())), 402);
        }

        throw new ModelNotFoundException(sprintf('%s could not found', class_basename($this->repository->getModelName())));
    }
}
