<?php
namespace CroudTech\RecurringTaskScheduler\Library\ScheduleParser;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;

abstract class Base
{
    /**
     * Array of generated dates
     *
     * @var array
     */
    protected $generated = [];

    /**
     * The schedule definition to parse
     *
     * @var array
     */
    protected $definition;

    protected $default_definition = [
        'range' => [],
        'time_of_day' => '09:00:00',
        'interval' => 1,
        'days' => [
            'mon' => true,
            'tue' => true,
            'wed' => true,
            'thu' => true,
            'fri' => true,
            'sat' => true,
            'sun' => true,
        ],
        'months' => [
            'jan' => true,
            'feb' => true,
            'mar' => true,
            'apr' => true,
            'may' => true,
            'jun' => true,
            'jul' => true,
            'aug' => true,
            'sep' => true,
            'oct' => true,
            'nov' => true,
            'dec' => true,
        ],
    ];

    /**
     * The start of the range
     *
     * @var Carbon
     */
    protected $range_start;

    /**
     * The timezone of the dates specified in the range
     *
     * @var string
     */
    protected $timezone = 'UTC';

    /**
     * The time of day the schedule should run
     *
     * @var string
     */
    protected $time_of_day;

    /**
     * The interval to use as a multiplier
     *
     * @var int
     */
    protected $interval;

    /**
     * Undocumented function
     *
     * @param array $definition
     */
    public function __construct(array $definition)
    {
        $this->definition = $this->setDefinition($definition);
    }

    /**
     * Check and parse format definition
     *
     * @param array $definition
     * @return void
     */
    protected function setDefinition(array $definition)
    {
        $this->definition = $this->addDefinitionDefaults($definition);
        $this->timezone = isset($this->definition['timezone']) ? $this->definition['timezone'] : $this->timezone;
        $this->range_start = is_a($this->definition['range'][0], Carbon::class) ? $this->definition['range'][0] : new Carbon($this->definition['range'][0], new \DateTimeZone($this->getTimezone()));
        $this->range_end = is_a($this->definition['range'][1], Carbon::class) ? $this->definition['range'][1] : new Carbon($this->definition['range'][1], new \DateTimeZone($this->getTimezone()));
        $this->time_of_day = $this->definition['time_of_day'];
        $this->interval = $this->definition['interval'];

        return $this->definition;
    }

    /**
     * Get the parsed definition array
     *
     * @return array
     */
    public function getDefinition() : array
    {
        return $this->definition;
    }

    /**
     * Set default definition values
     *
     * @return array
     */
    protected function addDefinitionDefaults($definition) : array
    {
        $this->default_definition['range'] = [
            0 => Carbon::now(),
            1 => Carbon::now()->addYear(),
        ];

        $merged_definition = collect($this->default_definition)->merge(collect($definition))->toArray();
        $merged_definition = $this->getDaysFromDefinition($merged_definition);

        return $merged_definition;
    }

    /**
     * Get parsed days array from definition
     *
     * @return void
     */
    protected function getDaysFromDefinition($definition)
    {
        $new_definition = $definition;
        // If days are set in the provided definition the merge with default days setting all other values to false
        if (isset($definition['days']) && is_array($definition['days'])) {
            $new_definition['days'] = collect($this->default_definition['days'])->map(
                function () {
                    return false;
                }
            )->merge($definition['days'])->toArray();
        }

        $new_definition['days'] = collect($new_definition['days'])->map(
            function ($var, $key) {
                if (strtolower($var) === $key) {
                    return true;
                }
                return filter_var($var, FILTER_VALIDATE_BOOLEAN);
            }
        )->toArray();

        return $new_definition;
    }

    /**
     * Get the range start date (as a copy to prevent modification)
     *
     * @return Carbon
     */
    public function getRangeStart() : Carbon
    {
        return $this->range_start->copy();
    }

    /**
     * Get the range start date as UTC (as a copy to prevent modification)
     *
     * @return Carbon
     */
    public function getRangeStartUTC() : Carbon
    {
        return $this->range_start->copy()->setTimezone('UTC');
    }

    /**
     * Get the range end date (as a copy to prevent modification)
     *
     * @return Carbon
     */
    public function getRangeEnd() : Carbon
    {
        return $this->range_end->copy();
    }

    /**
     * Get the range end date as UTC (as a copy to prevent modification)
     *
     * @return Carbon
     */
    public function getRangeEndUTC() : Carbon
    {
        return $this->range_end->copy()->setTimezone('UTC');
    }

    /**
     * Get thge timezone for all dates in the definition
     *
     * @return string
     */
    public function getTimezone() : string
    {
        return $this->timezone;
    }

    public function getTimeOfDay() : string
    {
        return $this->time_of_day;
    }

    /**
     * The interval
     *
     * @return int
     */
    public function getInterval() : int
    {
        return $this->interval;
    }
}
