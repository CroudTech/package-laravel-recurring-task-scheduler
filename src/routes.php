<?php
Route::resource('schedule', \CroudTech\RecurringTaskScheduler\Tests\App\Http\Controllers\ScheduleController::class);
Route::resource('schedule.schedule-event', \CroudTech\RecurringTaskScheduler\Tests\App\Http\Controllers\ScheduleEventController::class);