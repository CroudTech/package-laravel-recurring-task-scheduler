<?php
namespace CroudTech\RecurringTaskScheduler\Tests\App\Model;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Traits\ScheduleableTrait;
use Illuminate\Database\Eloquent\Model;

class TestScheduleable extends Model implements ScheduleableContract
{
    use ScheduleableTrait;

    protected $fillable = [
        'name',
    ];

    /**
     * Schedule callback
     *
     * @return boolean
     */
    public function trigger() : bool
    {
        return true;
    }
}
