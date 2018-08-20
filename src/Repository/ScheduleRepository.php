<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\Contracts\RepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Regeneration\ScheduleEventRegenerator;

class ScheduleRepository extends BaseRepository implements RepositoryContract, ScheduleRepositoryContract
{
    const DEFAULT_TIMEZONE = 'Europe/London';

    /**
     * Create a new schedule from a definition array
     *
     * @param array $definition
     * @param ScheduleableContract $scheduleable
     * @return Schedule
     */
    public function createFromScheduleDefinition(array $definition, ScheduleableContract $scheduleable = null) : Schedule
    {
        $parser = $this->getParserFromDefinition($definition);
        $schedule_attributes = $this->getTransformer()->transformDefinitionToScheduleAttributes($parser->getDefinition());
        $schedule_attributes = $this->preCreate($this->parseData($schedule_attributes));
        $schedule = $this->make($schedule_attributes);
        isset($scheduleable) ? $schedule->scheduleable()->associate($scheduleable) : null;
        $schedule->save();

        return $schedule;
    }

    /**
     * Update a schedule from a definition array
     *
     * @param integer $schedule_id
     * @param array $definition
     * @param ScheduleableContract $scheduleable
     * @return Schedule
     */
    public function updateFromScheduleDefinition(int $schedule_id, array $definition) : Schedule
    {
        $parser = $this->getParserFromDefinition($definition);
        $schedule_attributes = $this->getTransformer()->transformDefinitionToScheduleAttributes($parser->getDefinition());
        $schedule_attributes = $this->preCreate($this->parseData($schedule_attributes));
        $schedule = $this->find($schedule_id);
        $schedule->update($schedule_attributes);
        $schedule->save();

        return $schedule;
    }

    /**
     * Regenerate events for schedule
     *
     * @return Collection
     */   

    public function regenerateScheduleEvents($schedule, $from_today = false) : Collection
    {
        $parser = $this->getParserFromDefinition(
            $this->getDefinitionFromSchedule($schedule)
        );

        $today = \Carbon\Carbon::now()->setTimezone($schedule->timezone ?? self::DEFAULT_TIMEZONE)->startOfDay();

        $parser_dates = $parser->getDates();
        $current_dates = $schedule->scheduleEvents()->get()->pluck('date');
        $deprecated_dates = collect($current_dates)->diff($parser_dates)->toArray();
        $new_dates = collect($parser_dates)->diff($current_dates)->filter(function($date) use ($today, $from_today) {
            return !$from_today || $date >= $today;
        });

        $schedule->scheduleEvents()->whereIn('date', $deprecated_dates)->get()->each(function($scheduleEvent) {
            $scheduleEvent->delete();
        });

        collect($new_dates)->each(function($date) use($schedule) {
            $schedule->scheduleEvents()->create(['date' => $date]);
        });

        return $schedule->scheduleEvents()->get();
    }

    /**
     * Regenerate events for schedule
     *
     * @return Collection
     */
    public function updateScheduleEvents($schedule, $definition) : Collection
    {
        $parser = $this->getParserFromDefinition($definition);
        foreach ($parser->getDates() as $date) {
            $schedule->scheduleEvents()->each(function (ScheduleEvent $schedule_event) {
                $schedule_event->delete();
            });
            $schedule->scheduleEvents()->create(['date' => $date]);
        }
        return $schedule->scheduleEvents()->get();
    }

    /**
     * Delete events for schedule
     */
    public function deleteScheduleEvents($schedule)
    {
        $schedule->scheduleEvents->each(function(ScheduleEvent $schedule_event){
            $schedule_event->delete();
        });
    }
    
    /**
     * Make a new unsaved instance of the model
     *
     * @param array $data
     * @return void
     */
    public function make($data) : Schedule
    {
        $model_class = $this->getModelName();
        return new $model_class($this->parseData($data));
    }

    /**
     * Get a schedule parser from a definition array
     *
     * @param array $definition
     * @return ScheduleParserContract
     */
    public function getParserFromDefinition(array $definition) : ScheduleParserContract
    {
        return $this->container->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($definition);
    }

    /**
     * Get a schedule parser from a definition array
     *
     * @param Schedule $schedule
     * @return ScheduleParserContract
     */
    public function getParserFromSchedule(Schedule $schedule) : ScheduleParserContract
    {
        return $this->container->make(\CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory::class)->factory($this->getDefinitionFromSchedule($schedule));
    }

    /**
     * Get the definition array from a schedule model
     *
     * @param Schedule $schedule
     * @return array
     */
    public function getDefinitionFromSchedule($schedule) : array
    {
        return $this->getTransformer()->transformScheduleToDefinition($schedule);
    }
}
