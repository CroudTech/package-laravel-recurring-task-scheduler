<?php
namespace CroudTech\RecurringTaskScheduler\Repository;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleableContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Model\Schedule;
use CroudTech\Repositories\Contracts\RepositoryContract;

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
        foreach ($parser->getDates() as $date) {
            $schedule->scheduleEvents()->create(['date' => $date]);
        }
        return $this->postCreate($schedule_attributes, $schedule);
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
}
