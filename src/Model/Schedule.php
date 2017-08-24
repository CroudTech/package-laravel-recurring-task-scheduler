<?php
namespace CroudTech\RecurringTaskScheduler\Model;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyRelationshipQuery;
use Illuminate\Database\Eloquent\Relations\MorphTo as MorphToRelationshipQuery;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'timezone',
        'type',
        'range_start',
        'range_end',
        'time_of_day',
        'interval',
        'period',
        'day_of_month',
        'week_of_month',
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
        'scheduleable_id',
        'scheduleable_type',
        'occurrence',
    ];

    protected $attributes = [
        'timezone' => 'Europe/London',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'day_of_month' => 'string',
    ];

    /**
     * Polymorphic relationship to ScheduleableContract object
     *
     * @return MorphToRelationshipQuery
     */
    public function scheduleable() : MorphToRelationshipQuery
    {
        return $this->morphTo();
    }

    /**
     * Schedule events relationship
     *
     * @return HasManyRelationshipQuery
     */
    public function scheduleEvents() : HasManyRelationshipQuery
    {
        return $this->hasMany(ScheduleEvent::class);
    }

    /**
     * Future schedule events relationship
     *
     * @return HasManyRelationshipQuery
     */
    public function futureScheduleEvents() : HasManyRelationshipQuery
    {
        return $this->hasMany(ScheduleEvent::class)->where('date', '>', Carbon::now());
    }
}
