


# Recurring Task Scheduler

[![Build Status](http://drone.staging.croudcontrol.com/api/badges/croudtech/recurring_task_scheduler/status.svg)](http://drone.staging.croudcontrol.com/croudtech/recurring_task_scheduler)

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

### Routes

#### api/croudtech/schedule/{schedule}

Restful resource route for schedule objects

#### api/croudtech/schedule/{schedule}/schedule-event/{schedule_event}

Nested resource route for schedule events

#### api/croudtech/schedule/parse

Schedule parser. Pass a definition array in to get the matching dates. Doesn't save any data so is useful for previews


### Prerequisites

PHP 7 and Laravel 5.3^

### Versioning

[SemVer](http://semver.org/)

## Deployment

This package should be installed via composer following the package installation steps above

## Owner

Jim Robinson <jim.robinson@croud.co.uk>

# Documentation

## Schedule definition schema

 * Daily
 *  - Every N days {period, interval}
 *  - Every Workday {period, interval, modifier}
 *  - Every DOW {period, interval}
 * Weekly
 *  - N weeks {period, interval}
 *  - On DOW {period, interval, modifier}
 * Same day each month
 *  - Day N of every N months {period, interval, modifier}
 * Same week each month
 *  – every N months on the (1,2,3,4,last) DOW {period, interval, modifier}
 * Same day each year
 *  – Day Month {period, interval, modifier}
 * Same week each year
 *  – (1,2,3,4,last) DOW or Month {period, interval, modifier}



 ```mysql
SELECT Date_format(Now(), '%Y %c %w')
       AS
       current_formatted_day,
       Date_format(Now(), Concat('%Y ', months.month_number, ' ',
                          days.day_number)) AS
       schedule_formatted_day,
       months.month_name,
       months.month_number,
       days.day_name,
       days.day_number,
       schedules.*
FROM   schedules
       INNER JOIN (SELECT id,
                          'January' AS `month_name`,
                          '1'       AS `month_number`
                   FROM   schedules
                   WHERE  `jan` = 1
                   UNION ALL
                   SELECT id,
                          'February' AS `month_name`,
                          '2'        AS `month_number`
                   FROM   schedules
                   WHERE  `feb` = 1
                   UNION ALL
                   SELECT id,
                          'March' AS `month_name`,
                          '3'     AS `month_number`
                   FROM   schedules
                   WHERE  `mar` = 1
                   UNION ALL
                   SELECT id,
                          'April' AS `month_name`,
                          '4'     AS `month_number`
                   FROM   schedules
                   WHERE  `apr` = 1
                   UNION ALL
                   SELECT id,
                          'May' AS `month_name`,
                          '5'   AS `month_number`
                   FROM   schedules
                   WHERE  `may` = 1
                   UNION ALL
                   SELECT id,
                          'June' AS `month_name`,
                          '6'    AS `month_number`
                   FROM   schedules
                   WHERE  `jun` = 1
                   UNION ALL
                   SELECT id,
                          'July' AS `month_name`,
                          '7'    AS `month_number`
                   FROM   schedules
                   WHERE  `jul` = 1
                   UNION ALL
                   SELECT id,
                          'August' AS `month_name`,
                          '8'      AS `month_number`
                   FROM   schedules
                   WHERE  `aug` = 1
                   UNION ALL
                   SELECT id,
                          'September' AS `month_name`,
                          '9'         AS `month_number`
                   FROM   schedules
                   WHERE  `sep` = 1
                   UNION ALL
                   SELECT id,
                          'October' AS `month_name`,
                          '10'      AS `month_number`
                   FROM   schedules
                   WHERE  `oct` = 1
                   UNION ALL
                   SELECT id,
                          'November' AS `month_name`,
                          '11'       AS `month_number`
                   FROM   schedules
                   WHERE  `nov` = 1
                   UNION ALL
                   SELECT id,
                          'December' AS `month_name`,
                          '12'       AS `month_number`
                   FROM   schedules
                   WHERE  `dec` = 1) AS months
               ON months.id = schedules.id
       INNER JOIN (SELECT id,
                          'Sun' `day_name`,
                          '0'   AS `day_number`
                   FROM   schedules
                   WHERE  `sun` = 1
                   UNION ALL
                   SELECT id,
                          'Mon' `day_name`,
                          '1'   AS `day_number`
                   FROM   schedules
                   WHERE  `mon` = 1
                   UNION ALL
                   SELECT id,
                          'Tue' `day_name`,
                          '2'   AS `day_number`
                   FROM   schedules
                   WHERE  `tue` = 1
                   UNION ALL
                   SELECT id,
                          'Wed' `day_name`,
                          '3'   AS `day_number`
                   FROM   schedules
                   WHERE  `wed` = 1
                   UNION ALL
                   SELECT id,
                          'Thu' `day_name`,
                          '4'   AS `day_number`
                   FROM   schedules
                   WHERE  `thu` = 1
                   UNION ALL
                   SELECT id,
                          'Fri' `day_name`,
                          '5'   AS `day_number`
                   FROM   schedules
                   WHERE  `fri` = 1
                   UNION ALL
                   SELECT id,
                          'Sat' `day_name`,
                          '6'   AS `day_number`
                   FROM   schedules
                   WHERE  `sat` = 1) AS days
               ON days.id = schedules.id
WHERE
NOW() BETWEEN schedules.starts_at AND schedules.ends_at
AND Date_format(Now(), '%Y %c %w') = Date_format(Now(),
Concat('%Y ', months.month_number, ' ',
days.day_number))
AND (( schedules.period = 'every'
|| ( `period` = CASE
    WHEN Ceil(Day(
    Str_to_date('2017-07-28',
    '%Y-%m-%d')) / 7) = 1
     THEN
    'first'
    WHEN Ceil(Day(
    Str_to_date('2017-07-28',
    '%Y-%m-%d')) / 7) = 2
     THEN
    'second'
    WHEN Ceil(Day(
    Str_to_date('2017-07-28',
    '%Y-%m-%d')) / 7) = 3
     THEN
    'thrird'
    WHEN Ceil(Day(
    Str_to_date('2017-07-28',
    '%Y-%m-%d')) / 7) = 4
     THEN
    'fourth'
    WHEN Ceil(Day(
    Str_to_date('2017-07-28',
    '%Y-%m-%d')) / 7) = 4
     THEN
    'fifth'
    ELSE 'every'
  end )
  || (schedules.period = 'alternate' AND MOD(DAYOFYEAR(NOW()),2) = MOD(DAYOFYEAR(schedules.starts_at),2)
)))
AND deleted_at IS NULL;

 ```