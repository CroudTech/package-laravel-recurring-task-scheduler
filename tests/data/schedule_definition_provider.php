<?php
use Carbon\Carbon;

Carbon::setTestNow(Carbon::create(2017, 7, 20, 0, 0, 0, 'UTC'));

$edt_date = Carbon::create(2017, 7, 30, 10, 0, 0, 'MSK');
$utc_date = $edt_date->copy()->timezone('UTC');
dd($edt_date, $utc_date, $edt_date->diffInHours($utc_date));


$data =
[
    [
        [
            'timezone'      => $timezone = 'EDT',
            'range'         => [Carbon::now()->timezone($timezone), Carbon::now()->timezone($timezone)->addWeeks(3)],
            'period'        => 'days',
            'interval'      => 5,
            'time_of_day'   => $time_of_day = '09:30:00',
        ],
        [

            ($date = Carbon::create(2017, 7, 20, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->timezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 25, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->timezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 7, 30, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->timezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 4, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->timezone('UTC')->__toString(),
            ],
            ($date = Carbon::create(2017, 8, 9, 9, 30, 0, $timezone))->__toString() => [
                $date->__toString(),
                $date->copy()->timezone('UTC')->__toString(),
            ],
        ]
    ],
];
// Clear the mock current timestamp
Carbon::setTestNow();
var_export($data);
exit;
return $data;