<?php
namespace CroudTech\RecurringTaskScheduler\Console\Commands;

use CroudTech\RecurringTaskScheduler\Events\ScheduleExecuteEvent;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ExecuteCommand extends Command
{

    protected $name = 'croudtech:schedule:execute';
    protected $description = 'Execute schedules for the current timestamp';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command
     *
     * This just triggers an event.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Schedule executing');
        event(new ScheduleExecuteEvent);
    }
}
