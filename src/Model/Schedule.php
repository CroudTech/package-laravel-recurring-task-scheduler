<?php
namespace CroudTech\RecurringTaskScheduler\Model;

use Illuminate\Database\Eloquent\Model;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\SchedulableContract;
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

    /**
     * Polymorphic relationship to SchedulableContract object
     *
     * @return MorphToRelationshipQuery
     */
    public function schedulable() : MorphToRelationshipQuery
    {
        return $this->morphTo();
    }
}
