<?php
return [
    // 'Weekly with last day of range on the selected week day' => [
    //     '{
    //         "timezone": "Europe/London",
    //         "range": {
    //             "start": "2017-11-24",
    //             "end": "2018-01-01"
    //         },
    //         "time_of_day": "09:00",
    //         "type": "periodic",
    //         "interval": "1",
    //         "period": "weeks",
    //         "day_number": false,
    //         "week_number": false,
    //         "days": {
    //             "mon": true,
    //             "tue": false,
    //             "wed": false,
    //             "thu": false,
    //             "fri": false,
    //             "sat": false,
    //             "sun": false
    //         },
    //         "months": {}
    //     }',
    //     [
    //         '2017-11-27T09:00:00+00:00', // Mon 27 11 2017 09:00:00 +0000
    //         '2017-12-04T09:00:00+00:00', // Mon 04 12 2017 09:00:00 +0000
    //         '2017-12-11T09:00:00+00:00', // Mon 11 12 2017 09:00:00 +0000
    //         '2017-12-18T09:00:00+00:00', // Mon 18 12 2017 09:00:00 +0000
    //         '2017-12-25T09:00:00+00:00', // Mon 25 12 2017 09:00:00 +0000
    //         '2018-01-01T09:00:00+00:00', // Mon 25 12 2017 09:00:00 +0000
    //     ],
    // ],
    'Every two weeks on weekdays' => [   // Every two weeks on weekdays
        '{
            "definition_description": "Every two weeks on weekdays",
            "type": "periodic",
            "interval": "2",
            "period": "weeks",
            "day_number": false,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": false,
                "sun": false
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-27T23:00:00.000Z",
                "end": "2017-12-12T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-10-27T08:00:00+00:00', // Fri, 27 Oct 2017 08:00:00 +0000
            '2017-11-06T09:00:00+00:00', // Mon, 06 Nov 2017 09:00:00 +0000
            '2017-11-07T09:00:00+00:00', // Tue, 07 Nov 2017 09:00:00 +0000
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-09T09:00:00+00:00', // Thu, 09 Nov 2017 09:00:00 +0000
            '2017-11-10T09:00:00+00:00', // Fri, 10 Nov 2017 09:00:00 +0000
            '2017-11-20T09:00:00+00:00', // Mon, 20 Nov 2017 09:00:00 +0000
            '2017-11-21T09:00:00+00:00', // Tue, 21 Nov 2017 09:00:00 +0000
            '2017-11-22T09:00:00+00:00', // Wed, 22 Nov 2017 09:00:00 +0000
            '2017-11-23T09:00:00+00:00', // Thu, 23 Nov 2017 09:00:00 +0000
            '2017-11-24T09:00:00+00:00', // Fri, 24 Nov 2017 09:00:00 +0000
            '2017-12-04T09:00:00+00:00', // Mon, 04 Dec 2017 09:00:00 +0000
            '2017-12-05T09:00:00+00:00', // Tue, 05 Dec 2017 09:00:00 +0000
            '2017-12-06T09:00:00+00:00', // Wed, 06 Dec 2017 09:00:00 +0000
            '2017-12-07T09:00:00+00:00', // Thu, 07 Dec 2017 09:00:00 +0000
            '2017-12-08T09:00:00+00:00', // Fri, 08 Dec 2017 09:00:00 +0000
            '2017-12-11T09:00:00+00:00', // Mon, 11 Dec 2017 09:00:00 +0000
            '2017-12-12T09:00:00+00:00', // Tue, 12 Dec 2017 09:00:00 +0000
        ],
    ],
    'Every two weeks on wednesdays' => [   // Every two weeks on wednesdays
        '{
            "definition_description": "Every two weeks on wednesdays",
            "type": "periodic",
            "interval": "2",
            "period": "weeks",
            "day_number": false,
            "week_number": false,
            "days": {
                "mon": false,
                "tue": false,
                "wed": true,
                "thu": false,
                "fri": false,
                "sat": false,
                "sun": false
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-27T23:00:00.000Z",
                "end": "2017-12-12T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-22T09:00:00+00:00', // Wed, 22 Nov 2017 09:00:00 +0000
            '2017-12-06T09:00:00+00:00', // Wed, 06 Dec 2017 09:00:00 +0000
        ],
    ],
    'Every two weeks on wednesdays and fridays' => [   // Every two weeks on wednesdays and fridays
        '{
            "definition_description": "Every two weeks on wednesdays and fridays",
            "type": "periodic",
            "interval": "2",
            "period": "weeks",
            "day_number": false,
            "week_number": false,
            "days": {
                "mon": false,
                "tue": false,
                "wed": true,
                "thu": false,
                "fri": true,
                "sat": false,
                "sun": false
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-27T23:00:00.000Z",
                "end": "2017-12-12T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-10-27T08:00:00+00:00', // Fri, 27 Oct 2017 08:00:00 +0000
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-10T09:00:00+00:00', // Fri, 10 Nov 2017 09:00:00 +0000
            '2017-11-22T09:00:00+00:00', // Wed, 22 Nov 2017 09:00:00 +0000
            '2017-11-24T09:00:00+00:00', // Fri, 24 Nov 2017 09:00:00 +0000
            '2017-12-06T09:00:00+00:00', // Wed, 06 Dec 2017 09:00:00 +0000
            '2017-12-08T09:00:00+00:00', // Fri, 08 Dec 2017 09:00:00 +0000
        ],
    ],
    'Last working day modifier 1' => [   // Last working day modifier
        '{
            "definition_description": "Last working day modifier",
            "type": "periodic",
            "interval": "2",
            "period": "weeks",
            "day_number": false,
            "week_number": false,
            "days": {
                "mon": false,
                "tue": false,
                "wed": true,
                "thu": false,
                "fri": false,
                "sat": false,
                "sun": false
            },
            "months": {},
            "modifier": "last_working_day",
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-27T23:00:00.000Z",
                "end": "2017-12-12T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-10-27T08:00:00+00:00', // Fri, 27 Oct 2017 08:00:00 +0000
            '2017-11-10T09:00:00+00:00', // Fri, 10 Nov 2017 09:00:00 +0000
            '2017-11-24T09:00:00+00:00', // Fri, 24 Nov 2017 09:00:00 +0000
            '2017-12-08T09:00:00+00:00', // Fri, 08 Dec 2017 09:00:00 +0000
        ],
    ],
    'Last working day modifier 2' => [   // Last working day modifier
        '{
            "definition_description": "Last working day modifier",
            "type": "periodic",
            "interval": "1",
            "period": "weeks",
            "day_number": false,
            "week_number": false,
            "days": {
                "mon": false,
                "tue": false,
                "wed": true,
                "thu": false,
                "fri": true,
                "sat": false,
                "sun": false
            },
            "months": {},
            "modifier": "first_working_day",
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-27T23:00:00.000Z",
                "end": "2017-12-12T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-10-30T09:00:00+00:00', // Mon, 30 Oct 2017 09:00:00 +0000
            '2017-11-06T09:00:00+00:00', // Mon, 06 Nov 2017 09:00:00 +0000
            '2017-11-13T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
            '2017-11-20T09:00:00+00:00', // Mon, 20 Nov 2017 09:00:00 +0000
            '2017-11-27T09:00:00+00:00', // Mon, 27 Nov 2017 09:00:00 +0000
            '2017-12-04T09:00:00+00:00', // Mon, 07 Dec 2017 09:00:00 +0000
            '2017-12-11T09:00:00+00:00', // Mon, 11 Dec 2017 09:00:00 +0000
        ],
    ],
];