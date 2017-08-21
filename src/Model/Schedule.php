<?php
namespace CroudTech\RecurringTaskScheduler\Model;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo as MorphToRelationshipQuery;

class Schedule extends Model
{
    protected $fillable = [
        'timestamp',
        'range_start',
        'range_end',
    ];

    protected $attributes = [
        'timezone' => 'Europe/London',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
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
     * @return void
     */
    public function scheduleEvents()
    {
        return $this->hasMany(ScheduleEvent::class);
    }

    /**
     * Trigger the callback on the scheduleable object
     *
     * @return bool
     */
    public function trigger(ScheduleEvent $event) : bool
    {
        if ($return_value = $this->scheduleable()->first()->trigger($this, $event)) {
            $this->triggered_at = Carbon::now();
            $this->save();
        }
        return $return_value;
    }
}
