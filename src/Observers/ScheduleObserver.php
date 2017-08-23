<?php
namespace CroudTech\RecurringTaskScheduler\Observers;

use CroudTech\RecurringTaskScheduler\Contracts\ScheduleParserContract;
use CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleTransformer;
use CroudTech\RecurringTaskScheduler\Repository\ScheduleRepository;
use CroudTech\RecurringTaskScheduler\Model\Schedule;

class ScheduleObserver
{
    /**
     * The schedule transformer
     *
     * @var ScheduleTransformer
     */
    protected $transformer;

    /**
     * The schedule respository
     *
     * @var ScheduleRepository
     */
    protected $respository;

    /**
     * Constructor
     *
     * @param ScheduleTransformer $transformer
     */
    public function __construct(ScheduleTransformer $transformer, ScheduleRepository $respository)
    {
        $this->transformer = $transformer;
        $this->respository = $respository;
    }

    /**
     * Get the schedule transformer
     *
     * @return ScheduleTransformer
     */
    public function getTransformer() : ScheduleTransformer
    {
        return $this->transformer;
    }

    /**
     * Get the schedule respository
     *
     * @return ScheduleRepository
     */
    public function getRepository() : ScheduleRepository
    {
        return $this->respository;
    }

    /**
     * Get the parser for this event definition
     *
     * @param Schedule $schedule
     * @return void
     */
    public function getParser(Schedule $schedule)
    {
        $parser = $this->getParserFromDefinition($definition);
    }

    /**
     * Listen to the ScheduleEvent created event.
     *
     * @return void
     */
    public function created(Schedule $schedule)
    {
        $this->getRepository()->regenerateScheduleEvents($schedule);
    }
}