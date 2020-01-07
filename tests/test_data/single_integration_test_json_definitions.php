<?php

return collect([
    'Same week each month (with two days and excluded month) [every second monday and wednesday]' => [
        '{ "timezone": "Asia/Bahrain", "range": { "start": "2017-08-01", "end": "2017-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 1, "period": "months", "day_number": false, "week_number": "second", "days": { "mon": true, "wed": true }, "months": { "aug": true, "nov": true } }',
        [
            '2017-08-09T09:00:00+03:00', // Wed 09 Aug 2017 00:00:00 +01:00
            '2017-08-14T09:00:00+03:00', // Mon 14 Aug 2017 00:00:00 +01:00
            '2017-11-08T09:00:00+03:00', // Mon 08 Nov 2017 00:00:00 +01:00
            '2017-11-13T09:00:00+03:00', // Mon 13 Nov 2017 00:00:00 +01:00
        ],
    ],
])->map(function ($row) {
    foreach ($row[1] as $k => $expected_date) {
        $row[1][$k] = \Carbon\Carbon::parse($expected_date)->setTimezone('UTC')->format('c');
    }
    return $row;
})->toArray();
