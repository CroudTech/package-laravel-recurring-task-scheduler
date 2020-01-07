<?php

return collect([
    'Same day every three months spanning a new year' => [
        '{ "timezone": "Europe/London", "range": { "start": "2019-11-01", "end": "2020-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 3, "period": "months", "day_number": 1, "week_number": false, "days": { }, "months": { } }',
        [
            '2019-11-01T09:00:00+00:00', 
            '2020-02-01T09:00:00+00:00',
            '2020-05-01T08:00:00+00:00',
            '2020-08-01T08:00:00+00:00',
            '2020-11-01T09:00:00+00:00',
        ],
    ],
])->map(function ($row) {
    foreach ($row[1] as $k => $expected_date) {
        $row[1][$k] = \Carbon\Carbon::parse($expected_date)->setTimezone('UTC')->format('c');
    }
    return $row;
})->toArray();
