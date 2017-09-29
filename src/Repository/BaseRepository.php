<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\BaseRepository as CroudTechBaseRepository;
use CroudTech\Repositories\Contracts\RepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;

abstract class BaseRepository extends CroudTechBaseRepository
{
    /**
     * Return the model name for this repository
     *
     * @method getModelName
     * @return string
     */
    public function getModelName() : string
    {
        $config_key = 'scheduleable.repositories.' . snake_case(class_basename(static::class)) . '.model_class';
        return config($config_key);
    }

    /**
     * Default API paginate method using fractal to transform and include
     *
     * @param \Illuminate\Http\Request $request
     * @param boolean [$active_only=true] Only active records
     * @param array|null $with List of relationship to eager load
     * @return array
     */
    public function apiPaginate(Request $request, $active_only = true, array $with = null)
    {

        $per_page = $request->get('per_page', 15);
        $order_by = $request->get('order_by', ['id', 'asc']);

        $query_params = null;


        return $this->apiPaginateRepository($request, $with, $order_by, $per_page, $active_only);
    }

    /**
     *
     * @method apiPaginateRepository
     * @param  [type]                $order_by     [description]
     * @param  [type]                $query_params [description]
     * @param  integer               $per_page     [description]
     * @param  boolean               $active_only  [description]
     * @param  [type]                $with         [description]
     * @return [type]                              [description]
     */
    public function apiPaginateRepository(
        Request $request,
        array $with = null,
        $order_by = null,
        $per_page = 15,
        $active_only = true
    ) {
        if (is_string($order_by)) {
            $order_by = explode(',', $order_by);
            $order_by = array_map('trim', $order_by);
        }
        $model_name = $this->getModelName();

        if (empty($order_by)) {
            $order_by = ['id', 'asc'];
        } elseif (!isset($order_by[1])) {
            $order_by[1] = 'asc';
        }

        if (!empty($request->search)) {
            $this->query = $model_name::apiSearch($request);
        } else {
            $this->query = $this->query()->orderBy($order_by[0], $order_by[1]);
        }

        $query = $this->query()->orderBy($order_by[0], $order_by[1]);

        if ($with) {
            $this->query()->with($with);
        }

        if ($active_only && $this->hasColumn('status')) {
            $this->query()->whereIn('status', $model_name::$active_statuses);
        }

        $this->modifyApiPaginateQuery($request);

        $items = $this->paginate($per_page);

        $items->appends(\Request::only('search'))->links();
        $items->appends(\Request::only('per_page'))->links();
        $items->appends(\Request::only('order_by'))->links();

        return $items;
    }

    /**
     * @param Collection | QueryBuilder $this->query()
     * @param $request
     * @return void
     */
    protected function modifyApiPaginateQuery(Request $request)
    {
        if ($request->has('query')) {
            $this->modifyApiPaginateQueryRepository($request->get('query'));
        }
    }

    /**
     * modifyApiPaginateQueryRepository
     * @method modifyApiPaginateQueryRepository
     * @param  array    $query_params [description]
     * @return void
     */
    protected function modifyApiPaginateQueryRepository($query_params)
    {
        if (!empty($query_params)) {
            if ($is_eloquent_query = ($this->query() instanceof QueryBuilder || $this->query() instanceof Relation)) {
                $columns = Schema::getColumnListing($this->query()->getModel()->getTable());
            }

            if (isset($query_params['user_id'])) {
                $this->query()->where('user_id', $query_params['user_id']);
            }

            foreach ($query_params as $query_param => $query_param_value) {
                switch ($query_param) {
                    default:
                        if ($is_eloquent_query
                            && in_array($query_param, $columns)
                            || !$is_eloquent_query && $this->query()->has($query_param)
                        ) {
                            $this->query()->where($query_param, $query_param_value);
                        }
                        break;
                }
            }
        }
    }

    /**
     * Check to see if this table has a column. Save the result to the static $db_columns
     * property to prevent multiple queries to the information_schema table.
     *
     * @param $column
     * @return mixed
     */
    public function hasColumn($column)
    {
        $table = $this->container->make($this->getModelName())->getTable();

        if (!isset($this->db_columns[$table])) {
            $this->db_columns[$table] = \Schema::getColumnListing($table);
        }
        return in_array($column, $this->db_columns[$table]);
    }
}
