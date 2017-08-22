<?php
namespace CroudTech\RecurringTaskScheduler\Http\Requests;

class ScheduleFormRequest extends Request
{
    /**
     * Define validation rules for the schedule request
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'scheduleable_id' => 'required',
            'scheduleable_type' => 'required|is_scheduleable',
            'range.start' => 'required',
            'range.end' => 'required',
            'type' => 'required',
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