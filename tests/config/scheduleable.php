<?php

return [
    'repositories' => [
        'schedule_event_repository' => [
            'model_class' => \CroudTech\RecurringTaskScheduler\Model\ScheduleEvent::class
        ],
        'schedule_repository' => [
            'model_class' => \CroudTech\RecurringTaskScheduler\Model\Schedule::class
        ],
    ],
];