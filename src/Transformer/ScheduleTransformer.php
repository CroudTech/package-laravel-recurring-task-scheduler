<?php
namespace CroudTech\RecurringTaskScheduler\Transformer;

use \CroudTech\Repositories\Contracts\TransformerContract;

class ScheduleTransformer implements TransformerContract
{
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
