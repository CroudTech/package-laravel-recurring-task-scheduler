<?php

namespace CroudTech\RecurringTaskScheduler\Http\Controllers;

use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\Repositories\Fractal;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BaseController extends Controller
{
    /**
     * The repository for this resource
     *
     * @var RepositoryContract
     */
    protected $repository;

    /**
     * The Fractal class used for transforming resource data for the response
     *
     * @var Fractal
     */
    protected $fractal;

    /**
     * Construct Method
     *
     * @param Request                       $name The current request
     * @param \Illuminate\Auth\AuthManager  $auth_manager The auth manager
     * @param RepositoryContract            $repository
     */
    public function __construct(Request $request, RepositoryContract $repository = null)
    {
        $this->repository = $repository;
        $this->fractal = new Fractal($request);
    }

    /**
     * RESTful Show Method
     *
     * @param  Request $request Request
     * @return string
     */
    public function index(Request $request)
    {
        $items = $this->fractal->collection(
            $this->repository->apiPaginate($request),
            $this->repository->getTransformer(),
            $this->repository->getModelName()
        );
        return $this->sendResponse($items);
    }

    /**
     * RESTful Show Method
     *
     * @param  Request $request Request
     * @param  int $id Id
     * @return string
     */
    public function show(Request $request, $id)
    {
        if ($item = $this->repository->find($id)) {
            return $this->sendResponse($this->transform($item));
        }
        throw new ModelNotFoundException(sprintf('%s not found', class_basename($this->repository->getModelName())));
    }

    /**
     * RESTful Store Method
     *
     * @param  Request $request Request
     * @return string
     */
    public function apiStore(Request $request)
    {
        if ($item = $this->repository->create($request->all())) {
            $this->postStore($request, $item);
            return $this->sendResponse($this->transform($item));
        }

        throw new ModelNotFoundException(sprintf('%s not found', class_basename($this->repository->getModelName())));
    }

    /**
     * Run post apiStore actions
     *
     * Override in subclass and be sure to call this one too.
     *
     * @param Request $request
     * @param Model $model
     */
    public function postStore(Request $request, Model $model)
    {
        if ($request->has('meta') && is_array($request['meta'])) {
            $model->saveMetaValues($request['meta']);
        }
    }

    /**
     * RESTful Update method
     *
     * @param  Request $request Request
     * @param  int $id ID
     * @return string
     */
    public function update(Request $request, $id)
    {
        if ($item = $this->repository->find($id)) {
            if ($this->repository->update($id, $request->all())) {
                $this->postUpdate($request, $item);
                $item = $item->fresh();
                return $this->sendResponse($this->transform($item->fresh()));
            }

            throw new ApiException(sprintf('%s could not be saved', class_basename($this->repository->getModelName())), 402);
        }

        throw new ModelNotFoundException(sprintf('%s could not found', class_basename($this->repository->getModelName())));
    }

    /**
     * Run post apiUpdate actions
     *
     * Override in subclass and be sure to call this one too.
     *
     * @param Request $request
     * @param Model $model
     */
    public function postUpdate(Request $request, Model $model)
    {
        if ($request->has('meta') && is_array($request['meta'])) {
            $model->saveMetaValues($request['meta']);
        }
    }

    /**
     * RESTful destroy method
     *
     * @param Request $request Request
     * @param int $id Id
     * @return string
     */
    public function destroy(Request $request, $id)
    {
        if ($item = $this->repository->find($id)) {
            $this->repository->delete($id);
            return $this->sendSuccess(sprintf('%s deleted', class_basename($this->repository->getModelName())));
        }

        throw new ModelNotFoundException(sprintf('%s not found', class_basename($this->repository->getModelName())));
    }
    /**
     * Return transformed model
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        return $this->fractal->item(
            $item,
            $this->repository->getTransformer(),
            class_basename($this->repository->getModelName())
        );
    }

    /**
     * Send response
     * @param array $data Data
     * @param integer [$response_code=200] Response code
     * @return string
     */
    protected function sendResponse($data, $response_code = 200) {
        $data['success'] = true;
        return response()->json($data, $response_code, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Send success
     * @param string [$message='OK'] Message
     * @param integer [$response_code=200] Response code
     * @return string
     */
    protected function sendSuccess($message = 'OK', $response_code = 200) {
        return response()->json(['success' => true, 'message' => $message], $response_code);
    }

    /**
     * Send error
     * @param string [$message='Error'] Message
     * @param integer [$response_code=400] Response code
     * @return string
     */
    protected function sendError($message = 'Error', $response_code = 400) {
        return response()->json(['success' => false, 'message' => $message], $response_code);
    }
}
