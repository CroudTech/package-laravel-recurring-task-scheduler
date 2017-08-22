<?php

use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleController;
use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleEventController;

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::resource('schedule', ScheduleController::class);
    Route::resource('schedule.schedule-event', ScheduleEventController::class);
});
