<?php
use Carbon\Carbon;

Carbon::setTestNow(Carbon::create(2017, 7, 20, 0, 0, 0));

$data =
[
    [
        [
            'range'         => [Carbon::now(), Carbon::now()->addWeeks(3)],
            'period'        => 'days',
            'interval'      => 5,
            'timezone'      => $timezone = 'Europe/London',
            'time_of_day'   => $time_of_day = '09:30:00',
        ],
        [

            ($date = Carbon::create(2017, 7, 20, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 25, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 30, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 4, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 9, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
        ]
    ],
    [
        [
            'range'         => [Carbon::now(), Carbon::now()->addWeeks(3)],
            'period'        => 'days',
            'interval'      => 5,
            'timezone'      => $timezone = 'EDT',
            'time_of_day'   => $time_of_day = '09:30:00',
        ],
        [
            ($date = Carbon::create(2017, 7, 20, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 25, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 30, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 4, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 9, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
        ]
    ],
    [
        [
            'range'         => [Carbon::now(), Carbon::now()->addWeeks(1)],
            'period'        => 'days',
            'interval'      => 5,
            'timezone'      => $timezone = 'EDT',
            'time_of_day'   => $time_of_day = '09:30:00',
        ],
        [
            ($date = Carbon::create(2017, 7, 20, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 21, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 24, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 25, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 26, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 27, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 28, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 31, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 1, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 2, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 3, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 4, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 7, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 8, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 9, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 10, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->setTimezone('UTC')->__toString(),
            ],
        ]
    ],
];
// Clear the mock current timestamp
Carbon::setTestNow();
dd($data);
return $data;