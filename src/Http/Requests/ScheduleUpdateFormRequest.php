<?php
namespace CroudTech\RecurringTaskScheduler\Http\Requests;

class ScheduleUpdateFormRequest extends Request
{
    /**
     * Define validation rules for the schedule request
     *
     * This is for updates so we don't need to enforce validation as we can assume that the schedule already has all the required
     * fields and we're just updating specific ones.
     *
     * @return array
     */
    public function rules() : array
    {
        return [

        ];
    }

    /**
     * Authorise the user for this form request
     *
     * @return void
     */
    public function authorize()
    {
        return true;
    }
}