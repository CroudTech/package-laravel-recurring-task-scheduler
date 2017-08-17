<?php
namespace CroudTech\RecurringTaskScheduler\Model;

use Illuminate\Database\Eloquent\Model;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

class Schedule extends Model
{
    /**
     * The object used to parse the schedule definition into a collection of dates
     *
     * @var ScheduleParserContract
     */
    protected $schedule_parser;
}
