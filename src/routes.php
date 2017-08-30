<?php

use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleController;
use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleEventsNestedController;

Route::group(['prefix' => 'api/croudtech', 'middleware' => ['api']], function () {
    Route::resource('schedule', ScheduleController::class);
    Route::resource('schedule.schedule-event', ScheduleEventsNestedController::class);
});
