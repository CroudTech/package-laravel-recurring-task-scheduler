<?php
namespace CroudTech\RecurringTaskScheduler\Tests\App\Transformers;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\Contracts\TransformerContract;
use League\Fractal\TransformerAbstract;
use CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable;

class TestScheduleableTransformer extends TransformerAbstract implements TransformerContract
{
    protected $availableIncludes = [];

    /**
     * Transform the scheduleable item
     *
     * @param TestScheduleable $scheduleable
     * @return array
     */
    public function transform(TestScheduleable $scheduleable) : array
    {
        return $scheduleable->toArray();
    }
}
