<?php
namespace CroudTech\RecurringTaskScheduler\Transformer;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\Contracts\TransformerContract;
use League\Fractal\TransformerAbstract;

class ScheduleTransformer extends TransformerAbstract implements TransformerContract
{
    protected $availableIncludes = [
        'all_schedule_events',
        'future_schedule_events',
        'past_schedule_events',
    ];

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
        $schedule_array = $this->transformScheduleToDefinition($schedule);
        $schedule_array['created_at'] = Carbon::parse($schedule_array['created_at']);
        $schedule_array['updated_at'] = Carbon::parse($schedule_array['updated_at']);
        return $schedule_array;
    }

    /**
     * Convert flat schedule attributes to definition array
     *
     * @param Schedule $schedule
     * @return void
     */
    public function transformScheduleToDefinition(Schedule $schedule)
    {
        $schedule_array = $schedule->fresh()->toArray();

        foreach ($this->transform_exclusions as $exclusion) {
            unset($schedule_array[$exclusion]);
        }

        foreach ($this->days as $day) {
            if (isset($schedule[$day])) {
                $schedule_array['days'][$day] = $schedule[$day];
            }
            unset($schedule_array[$day]);
        }

        foreach ($this->months as $month) {
            if (isset($schedule[$month])) {
                $schedule_array['months'][$month] = $schedule[$month];
            }
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
        $parser = app()->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition);
        $attributes = $parser->getDefinition();
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

    /**
     * Include User
     *
     * @param Event $event
     * @return \League\Fractal\ItemResource
     */
    public function includeOwner(Event $event)
    {
        if (!$event->owner) {
            return;
        }
        return $this->item($event->owner, new \App\Transformers\UserTransformer);
    }

    /**
     * Include all the events attached to this schedule
     *
     * @return void
     */
    public function includeAllScheduleEvents(Schedule $schedule)
    {
        return $this->collection($schedule->scheduleEvents, new ScheduleEventSimpleTransformer);
    }

    /**
     * Include all the events attached to this schedule
     *
     * @return void
     */
    public function includeFutureScheduleEvents(Schedule $schedule)
    {
        return $this->collection($schedule->futureScheduleEvents, new ScheduleEventSimpleTransformer);
    }
}
