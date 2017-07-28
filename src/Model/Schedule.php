<?php
namespace CroudTech\RecurringTaskScheduler\Model;

use Illuminate\Database\Eloquent\Model;
use CroudTech\RecurringTaskScheduler\Contracts\SchedulePeriodParserContract;

class Schedule extends Model
{
    /**
     * The object used to parse the schedule definition into a collection of dates
     *
     * @var SchedulePeriodParserContract
     */
    protected $schedule_parser;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [], SchedulePeriodParserContract $schedule_parser = null)
    {
        $this->schedule_parser = $schedule_parser;
        return parent::__construct($attributes);
    }
}
