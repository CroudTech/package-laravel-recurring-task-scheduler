<?php
namespace CroudTech\RecurringTaskScheduler\Tests\App\Model;

use CroudTech\RecurringTaskScheduler\Traits\ScheduleableTrait;
use Illuminate\Database\Eloquent\Model;

class TestSchedulable extends Model
{
    use ScheduleableTrait;

    protected $fillable = [
        'name',
    ];
}
