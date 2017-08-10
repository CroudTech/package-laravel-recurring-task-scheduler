<?php
namespace CroudTech\RecurringTaskScheduler\Contracts;

interface ScheduleParserContract
{
    /**
     * Return generated dates from provided schedule definition
     *
     * @return Collection
     */
    public function getDates() : array;
}
