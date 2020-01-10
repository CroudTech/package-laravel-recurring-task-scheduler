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
        $today = $timestamp->format('Y-m-d');
        return $this->query()
            ->whereBetween('date', [$timestamp->copy()->subDays(1)->startOfDay(), $timestamp])
            ->whereHas('schedule', function ($query) {
                $query->where('status', '=', 'active');
            })
            ->whereNull('triggered_at')
            ->where('paused', '=', 0)
            ->with('schedule')
            ->get()
            ->filter(function ($scheduleEvent) use ($today) {
                if ($scheduleEvent->date->format('Y-m-d') === $today) {
                    return true;
                }
                return $scheduleEvent->date->copy()->timezone($scheduleEvent->schedule->timezone)->format('Y-m-d') === $today;
            });
    }
}
