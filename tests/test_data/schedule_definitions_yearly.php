<?php
return [
    'Same day each year [16th August every year]' => [
        '{
            "timezone": "Europe/London",
            "range": {
                "start": "2017-03-13",
                "end": "2017-11-30"
            },
            "time_of_day": "09:00",
            "type": "periodic",
            "interval": 1,
            "period": "years",
            "day_number": "16",
            "week_number": false,
            "days": {},
            "months": {
                "jan": false,
                "feb": false,
                "mar": false,
                "apr": false,
                "may": false,
                "jun": false,
                "jul": false,
                "aug": true,
                "sep": false,
                "oct": false,
                "nov": false,
                "dec": false
            }
        }',
        [
            '2017-08-16T08:00:00+00:00', // Wed 16 Aug 2017 00:00:00 +01:00
        ]
    ],
    'Same week each year [last Monday of August every year]' => [
        '{
            "timezone": "Europe/London",
            "range": {
                "start": "2017-08-16",
                "end": "2019-11-30"
            },
            "time_of_day": "09:00",
            "type": "periodic",
            "interval": 1,
            "period": "years",
            "day_number": false,
            "modifier": "last_week_of_month",
            "days": {
                "tue": true,
                "thu": true
            },
            "months": {
                "aug": true
            }
        }',
        [
            '2017-08-29T08:00:00+00:00', // Tue, 29 Aug 2017 08:00:00 +0000
            '2017-08-31T08:00:00+00:00', // Thu, 31 Aug 2017 08:00:00 +0000
            '2018-08-28T08:00:00+00:00', // Tue, 28 Aug 2018 08:00:00 +0000
            '2018-08-30T08:00:00+00:00', // Thu, 30 Aug 2018 08:00:00 +0000
            '2019-08-27T08:00:00+00:00', // Tue, 27 Aug 2019 08:00:00 +0000
            '2019-08-29T08:00:00+00:00', // Thu, 29 Aug 2019 08:00:00 +0000
        ]
    ],
    'Same week each year [last Monday of August every year] but with invalid day (there is no tuesday in the last week of july 2017)' => [
        '{
            "timezone": "Europe/London",
            "range": {
                "start": "2017-03-16",
                "end": "2019-11-30"
            },
            "time_of_day": "09:00",
            "type": "periodic",
            "interval": 1,
            "period": "years",
            "day_number": false,
            "modifier": "last_week_of_month",
            "days": {
                "mon": true,
                "tue": true
            },
            "months": {
                "jul": true
            }
        }',
        [
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2018-07-30T08:00:00+00:00', // Mon, 30 Jul 2018 08:00:00 +0000
            '2018-07-31T08:00:00+00:00', // Tue, 31 Jul 2018 08:00:00 +0000
            '2019-07-29T08:00:00+00:00', // Mon, 29 Jul 2019 08:00:00 +0000
            '2019-07-30T08:00:00+00:00', // Tue, 30 Jul 2019 08:00:00 +0000
        ]
    ],
];