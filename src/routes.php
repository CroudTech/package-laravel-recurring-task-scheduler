<?php

use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleController;
use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleEventsNestedController;
use CroudTech\RecurringTaskScheduler\Http\Controllers\ScheduleParserController;

Route::group(['prefix' => 'api/croudtech', 'middleware' => ['api']], function () {
    Route::get('schedule/parse', ScheduleParserController::class . '@parse')->name('croudtech.schedule.parse');
    Route::resource('schedule', ScheduleController::class);
    Route::resource('schedule.schedule-event', ScheduleEventsNestedController::class);
});
