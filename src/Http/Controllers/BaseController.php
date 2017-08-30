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
    public function __construct(Request $request, AuthManager $auth_manager)
    {
        $this->fractal = new Fractal($request);
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
