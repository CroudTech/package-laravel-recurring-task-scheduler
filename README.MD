


# Recurring Task Scheduler

[Link/Badge]() to CI server (if one exists)

PHP Laravel Package to provide scheduler functionality.

A SchedulableTrait is provided which allows you to link elqoquent models to a schedule model.

The scheduler generates a collection of future schedule event records and has a command line task that executes the schedule callback on the attached object.

## Package Installation

composer require croudtech/recurring_task_scheduler

Add the schedulable provider to the providers list in your laravel config/app.php file:

```php
[
    ...
    'providers' => [
        ...
        \CroudTech\RecurringTaskScheduler\RecurringTaskSchedulerServiceProvider::class,
        ...
    ],
    ...
]
```

A schedulable model must use the `RecurringTaskSchedulableTrait` and implement `RecurringTaskSchedulableInterface`.

The `RecurringTaskSchedulableInterface` interface expects a `recurringTaskScheduleCallback()` method which is called when the schedule is executed.

```php
class Task extends Model {
    public function recurringTaskScheduleCallback(\CroudTech\RecurringTaskScheduler\ScheduleEvent $schedule_event) : boolean
    {
        try {
            ...do something here...
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
```

### Prerequisites

PHP 7 and Laravel 5.3^

### Versioning

[SemVer](http://semver.org/)

## Deployment

This package should be installed via composer following the package installation steps above

## Owner

Jim Robinson <jim.robinson@croud.co.uk>