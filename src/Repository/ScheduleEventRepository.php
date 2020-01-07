<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleEventRepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\Repositories\Contracts\RepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ScheduleEventRepository extends BaseRepository implements RepositoryContract, ScheduleEventRepositoryContract
{
    /**
     * @param Collection | QueryBuilder $this->query()
     * @param $request
     * @return void
     */
    protected function modifyApiPaginateQuery(Request $request)
    {
        if (empty($request['all_events'])) {
            $this->query()->todaysEvents();
        }

        unset($request['all_events']);

        parent::modifyApiPaginateQuery($request);
    }

    /**
     * Get all events for the specified timestamp
     *
     * @return Collection
     */
    public function getEventsForTimestamp(Carbon $timestamp) : Collection
    {
        return $this->query()
            ->whereBetween('date', [$timestamp->copy()->subDays(1)->startOfDay(), $timestamp])
            ->whereHas('schedule', function ($query) {
                $query->where('status', '=', 'active');
            })
            ->whereNull('triggered_at')
            ->where('paused', '=', 0)
            ->with('schedule')
            ->get()
            ->reject(function ($scheduleEvent) use ($timestamp) {
                return Carbon::parse($scheduleEvent->date)->timezone($scheduleEvent->schedule->timezone)->format('Y-m-d') !== $timestamp->format('Y-m-d');
            });
    }
}
