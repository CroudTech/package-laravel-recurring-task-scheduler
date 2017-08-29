<?php
namespace CroudTech\RecurringTaskScheduler\Tests\App\Model;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Events\ScheduleEventTriggerEvent;
use CroudTech\RecurringTaskScheduler\Traits\ScheduleableTrait;
use Illuminate\Database\Eloquent\Model;

class TestScheduleable extends Model implements ScheduleableContract
{
    use ScheduleableTrait;

    protected $fillable = [
        'name',
        'test_success',
    ];

    protected $casts = [
        'test_success' => 'boolean',
    ];

    protected $attributes = [
        'test_success' => true,
    ];

    /**
     * Schedule callback
     *
     * @return boolean
     */
    public function scheduleEventTrigger(ScheduleEventTriggerEvent $event) : bool
    {
        return $this->test_success;
    }
}
