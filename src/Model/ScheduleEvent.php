<?php
namespace CroudTech\RecurringTaskScheduler\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Exceptions\InvalidScopeException;

/**
 * The ScheduleEvent represents a single date generated by a schedule.
 *
 * Datetime values stored in the ScheduleEvent are stored in UTC (Timezone offsets are assumed to have been calculated at generation time)
 * and a copy of the original datetime with it's timezone are stored for reporting and debugging
 */
class ScheduleEvent extends Model
{
    use SoftDeletes;
    
    protected $table = 'ctrts_schedule_events';

    protected $fillable = [
        'date',
        'original_date',
        'schedule_id',
        'trigger_success',
        'triggered_at',
        'paused',
    ];

    protected $casts = [
        'modified' => 'boolean',
        'trigger_success' => 'boolean',
        'triggered_at' => 'datetime',
        'schedule_id' => 'int',
        'paused' => 'boolean',
    ];

    protected $attributes = [
        'trigger_success' => null,
    ];

    protected $dates = [
        'created_at',
        'date',
        'deleted_at',
        'original_date',
        'triggered_at',
        'updated_at',
    ];

    /**
     * Schedule relationship
     *
     * @return void
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Trigger the callback on the schedule
     *
     * @return bool
     */
    public function trigger()
    {
        event(new \CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent($this));
    }

    /**
     * Scope a query to only include future events
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFutureEvents($query)
    {
        return $query->where('date', '>', Carbon::now());
    }

     /**
      * Scope a query to only include todays events (based on the schedules timezone)
      *
      * @param  \Illuminate\Database\Eloquent\Builder $query
      * @return \Illuminate\Database\Eloquent\Builder
      */
    public function scopeTodaysEvents($query)
    {
        switch ($grammar = class_basename(get_class($query->toBase()->getGrammar())))
        {
            case 'SQLiteGrammar':
                throw new InvalidScopeException(sprintf('The scope method %s is not implemented for sql grammar %s', __METHOD__, $grammar));
                    break;
            case 'MySqlGrammar':
                $schedule_table = (new Schedule)->getTable();
                $schedule_event_table = (new static)->getTable();
                $query->join($schedule_table, $schedule_event_table . '.schedule_id', '=', $schedule_table . '.id');
                $query->select($schedule_event_table . '.*');
                return $query->whereRaw('date >= CONVERT_TZ(DATE_FORMAT(CONVERT_TZ(NOW(), \'UTC\', ' . $schedule_table . '.timezone),"%Y-%m-%d 00:00:00"), ' . $schedule_table . '.timezone, \'UTC\')');
                    break;
        }
    }
}
