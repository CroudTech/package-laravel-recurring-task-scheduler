<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\RecurringTaskScheduler\Model\ScheduleEvent;
use CroudTech\Repositories\Contracts\RepositoryContract;
use Illuminate\Support\Collection;

class ScheduleRepository extends BaseRepository implements RepositoryContract, ScheduleRepositoryContract {

    /**
     * Return the model name for this repository
     *
     * @method getModelName
     * @return string
     */
    public function getModelName() : string
    {
        return Schedule::class;
    }

    /**
     * Create a new schedule from a definition array
     *
     * @param array $definition
     * @param ScheduleableContract $scheduleable
     * @return Schedule
     */
    public function createFromScheduleDefinition(array $definition, ScheduleableContract $scheduleable) : Schedule
    {
        $parser = $this->getParserFromDefinition($definition);
        $schedule_attributes = $this->getTransformer()->transformDefinitionToScheduleAttributes($parser->getDefinition());
        $schedule_attributes = $this->preCreate($this->parseData($schedule_attributes));
        $schedule = $this->make($schedule_attributes);
        $schedule->scheduleable()->associate($scheduleable);
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
    public function regenerateScheduleEvents($schedule) : Collection
    {
        $definition = $this->getDefinitionFromSchedule($schedule);
        $parser = $this->getParserFromDefinition($definition);
        $schedule->scheduleEvents()->each(function (ScheduleEvent $schedule_event) {
            $schedule_event->delete();
        });

        foreach ($parser->getDates() as $date) {
            $schedule->scheduleEvents()->create(['date' => $date]);
        }

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