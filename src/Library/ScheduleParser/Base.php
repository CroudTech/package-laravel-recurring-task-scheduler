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
        'period' => 'days',
        'day_of_month' => false,
        'week_of_month' => false,
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
        $this->definition = $this->addDefinitionDefaults($this->replaceNumericRangeKeys($definition));
        $this->timezone = isset($this->definition['timezone']) ? $this->definition['timezone'] : $this->timezone;
        $this->definition['timezone'] = $this->timezone;
        $this->definition['day_of_month'] = empty($this->definition['day_of_month']) ? false : $this->definition['day_of_month'];
        $this->definition['week_of_month'] = empty($this->definition['week_of_month']) ? false : $this->definition['week_of_month'];
        $this->parseDefinitionRange();
        $this->time_of_day = $this->definition['time_of_day'];
        $this->interval = $this->definition['interval'];

        return $this->definition;
    }

    /**
     * Replace numeric keys on range array and slice array to 2 items
     *
     * @return array
     */
    protected function replaceNumericRangeKeys($definition) : array
    {
        // Deal with numeric keys on date range
        if (isset($definition['range']) && is_array($definition['range'])) {
            $definition['range'] = array_slice($definition['range'], 0, 2);
            if (is_numeric(array_keys($definition['range'])[0])) {
                $definition['range'] = array_combine(['start','end'], $definition['range']);
            }
        }

        return $definition;
    }

    protected function parseDefinitionRange()
    {
        if (is_array($this->definition['range'])) {
            $this->definition['range'] = array_slice($this->definition['range'], 0, 2);
        }

        $this->range_start = is_a($this->definition['range']['start'], Carbon::class) ? $this->definition['range']['start'] : new Carbon($this->definition['range']['start'], new \DateTimeZone($this->getTimezone()));
        $this->range_end = is_a($this->definition['range']['end'], Carbon::class) ? $this->definition['range']['end'] : new Carbon($this->definition['range']['end'], new \DateTimeZone($this->getTimezone()));
        $this->range_start->setTime(0, 0, 0);
        $this->range_end->setTime(23, 59, 59);
        $this->definition['range']['start'] = $this->getRangeStart()->format('c');
        $this->definition['range']['end'] = $this->getRangeEnd()->format('c');
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
            'start' => Carbon::now(),
            'end' => Carbon::now()->addYear(),
        ];

        $merged_definition = collect($this->default_definition)->merge(collect($definition))->toArray();
        $merged_definition = $this->getDaysFromDefinition($merged_definition);
        $merged_definition = $this->getMonthsFromDefinition($merged_definition);

        return $merged_definition;
    }

    /**
     * Get parsed days array from definition
     *
     * @return void
     */
    protected function getDaysFromDefinition($definition)
    {
        return $this->getDefaultsFromDefinition($definition, 'days');
    }

    /**
     * Get parsed months array from definition
     *
     * @return void
     */
    protected function getMonthsFromDefinition($definition)
    {
        return $this->getDefaultsFromDefinition($definition, 'months');
    }

    /**
     * Get parsed days array from definition
     *
     * @return void
     */
    protected function getDefaultsFromDefinition($definition, $definition_key)
    {
        // Deal with empty definition key
        if (empty($definition[$definition_key])) {
            $definition[$definition_key] = $this->default_definition[$definition_key];
            return $definition;
        }

        $new_definition = $definition;
        // If keys are set in the provided definition the merge with default keys setting all other values to false
        if (isset($definition[$definition_key]) && is_array($definition[$definition_key])) {
            $new_definition[$definition_key] = collect($this->default_definition[$definition_key])->map(
                function () {
                    return false;
                }
            )->merge($definition[$definition_key])->toArray();
        }

        $new_definition[$definition_key] = collect($new_definition[$definition_key])->map(
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

    /**
     * Return generated dates from provided schedule definition
     *
     * @return Collection
     */
    public function filterExceptions($generated)
    {
        return collect($generated)->filter(function ($date) {
            $day = strtolower($date->format('D'));
            $month = strtolower($date->format('M'));
            return array_key_exists($day, $this->definition['days']) &&
                $this->definition['days'][$day] === true &&
                array_key_exists($month, $this->definition['months']) &&
                $this->definition['months'][$month] === true;
        })->values()->toArray();
    }

    /**
     * Convert weekday name formatting
     *
     * @param string $day_name in short format (mon,tue etc...)
     * @return string
     */
    protected function formatShortDay($day_name, $format)
    {
        $date = \Carbon\Carbon::parse('2017-08-28');
        $last_date = $date->copy()->addWeek(1);
        while ($date->lte($last_date)) {
            $formats = ['D', 'l', 'N', 'w'];
            $days[strtolower($date->format('D'))] = [];
            foreach ($formats as $current_format) {
                $days[strtolower($date->format('D'))][$current_format] = $date->format($current_format);
            }
            $date->addDay(1);
        }
        return isset($days[strtolower($day_name)][$format]) ? $days[strtolower($day_name)][$format] : $day_name;
    }

    /**
     * Sort generated dates
     *
     * @return void
     */
    protected function sortDates()
    {
        usort($this->generated, function($a, $b) {
            return $a->gt($b);
        });
    }
}
