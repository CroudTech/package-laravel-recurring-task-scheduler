<?php
namespace CroudTech\RecurringTaskScheduler\Transformer;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\Contracts\TransformerContract;
use League\Fractal\TransformerAbstract;

class ScheduleTransformer extends TransformerAbstract implements TransformerContract
{
    protected $days = [
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
    ];

    protected $months = [
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
    ];

    /**
     * Keys to exclude from the transformed array
     *
     * @var array
     */
    protected $transform_exclusions = [
        'scheduleable_type',
        'scheduleable_id',
        'scheduleable',
    ];

    /**
     * Transform the schedule for endpoints
     *
     * This returns the parsed definition as created by the parser class
     *
     * The related schedulable object is added via an include
     *
     * @param Schedule $schedule
     * @return array
     */
    public function transform(Schedule $schedule) : array
    {
        $schedule_array = $schedule->toArray();

        foreach ($this->transform_exclusions as $exclusion) {
            unset($schedule_array[$exclusion]);
        }

        foreach ($this->days as $day) {
            $schedule_array['days'][$day] = $schedule[$day];
            unset($schedule_array[$day]);
        }

        foreach ($this->months as $month) {
            $schedule_array['months'][$month] = $schedule[$month];
            unset($schedule_array[$month]);
        }

        $schedule_array['range']['start'] = $schedule_array['range_start'];
        unset($schedule_array['range_start']);
        $schedule_array['range']['end'] = $schedule_array['range_end'];
        unset($schedule_array['range_end']);
        $parser = app()->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($schedule_array);
        $schedule_array = $parser->getDefinition();
        $schedule_array['range']['start'] = Carbon::parse($schedule_array['range']['start']);
        $schedule_array['range']['end'] = Carbon::parse($schedule_array['range']['end']);
        $schedule_array['created_at'] = Carbon::parse($schedule_array['created_at']);
        $schedule_array['updated_at'] = Carbon::parse($schedule_array['updated_at']);
        return $schedule_array;
    }

    /**
     * Convert a schedule array into the attributes required by a schedule model
     *
     * @param array $definition
     * @return array
     */
    public function transformDefinitionToScheduleAttributes(array $definition) : array
    {
        $attributes = $definition;
        $attributes['range_start'] = $attributes['range']['start'];
        $attributes['range_end'] = $attributes['range']['end'];
        unset($attributes['range']);
        foreach ($definition['days'] as $day => $day_val) {
            $attributes[$day] = $day_val;
        }
        unset($attributes['days']);
        foreach ($definition['months'] as $day => $day_val) {
            $attributes[$day] = $day_val;
        }
        unset($attributes['months']);

        return $attributes;
    }
}
