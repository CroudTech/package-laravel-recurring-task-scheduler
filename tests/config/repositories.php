<?php

use CroudTech\RecurringTaskScheduler\Repository\Contracts\ScheduleEventRepositoryContract;
use CroudTech\RecurringTaskScheduler\Repository\Contracts\ScheduleRepositoryContract;
use CroudTech\RecurringTaskScheduler\Repository\ScheduleEventRepository;
use CroudTech\RecurringTaskScheduler\Repository\ScheduleRepository;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleEventTransformer;
use CroudTech\RecurringTaskScheduler\Transformer\ScheduleTransformer;

return [
    'repositories' => [
        ScheduleEventRepositoryContract::class => ScheduleEventRepository::class,
        ScheduleRepositoryContract::class => ScheduleRepository::class,
    ],

    'repository_transformers' => [
        ScheduleEventRepository::class => ScheduleEventTransformer::class,
        ScheduleRepository::class => ScheduleTransformer::class,
    ],

    'contextual_repositories' => [],
];