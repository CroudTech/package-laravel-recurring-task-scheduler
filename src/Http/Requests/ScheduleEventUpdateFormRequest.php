<?php
namespace CroudTech\RecurringTaskScheduler\Http\Requests;

class ScheduleEventUpdateFormRequest extends Request
{
    /**
     * Define validation rules for the schedule request
     *
     * @return array
     */
    public function rules() : array
    {
        return [];
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