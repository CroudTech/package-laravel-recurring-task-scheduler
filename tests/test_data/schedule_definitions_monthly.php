<?php
return [
    [
        '{
            "definition_description": "Every month on the 27th (set by start date)",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "day_number": false,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-27T23:00:00.000Z",
                "end": "2018-01-30T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-10-27T08:00:00+00:00', // Fri, 27 Oct 2017 08:00:00 +0000
            '2017-11-27T09:00:00+00:00', // Mon, 27 Nov 2017 09:00:00 +0000
            '2017-12-27T09:00:00+00:00', // Wed, 27 Dec 2017 09:00:00 +0000
            '2018-01-27T09:00:00+00:00', // Sat, 27 Jan 2018 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Every month on the 27th (set by day_number)",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "day_number": 27,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-10-21T23:00:00.000Z",
                "end": "2018-01-30T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-10-27T08:00:00+00:00', // Fri, 27 Oct 2017 08:00:00 +0000
            '2017-11-27T09:00:00+00:00', // Mon, 27 Nov 2017 09:00:00 +0000
            '2017-12-27T09:00:00+00:00', // Wed, 27 Dec 2017 09:00:00 +0000
            '2018-01-27T09:00:00+00:00', // Sat, 27 Jan 2018 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Every month on the 31st making sure invalid dates aren\'t created (or dates aren\'t rolled over to the next month)",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "day_number": 31,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-01-01T23:00:00.000Z",
                "end": "2018-12-31T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-01-31T09:00:00+00:00', // Tue, 31 Jan 2017 09:00:00 +0000
            '2017-03-31T08:00:00+00:00', // Fri, 31 Mar 2017 08:00:00 +0000
            '2017-05-31T08:00:00+00:00', // Wed, 31 May 2017 08:00:00 +0000
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2017-08-31T08:00:00+00:00', // Thu, 31 Aug 2017 08:00:00 +0000
            '2017-10-31T09:00:00+00:00', // Tue, 31 Oct 2017 09:00:00 +0000
            '2017-12-31T09:00:00+00:00', // Sun, 31 Dec 2017 09:00:00 +0000
            '2018-01-31T09:00:00+00:00', // Wed, 31 Jan 2018 09:00:00 +0000
            '2018-03-31T08:00:00+00:00', // Sat, 31 Mar 2018 08:00:00 +0000
            '2018-05-31T08:00:00+00:00', // Thu, 31 May 2018 08:00:00 +0000
            '2018-07-31T08:00:00+00:00', // Tue, 31 Jul 2018 08:00:00 +0000
            '2018-08-31T08:00:00+00:00', // Fri, 31 Aug 2018 08:00:00 +0000
            '2018-10-31T09:00:00+00:00', // Wed, 31 Oct 2018 09:00:00 +0000
            '2018-12-31T09:00:00+00:00', // Mon, 31 Dec 2018 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Start on an invalid date",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "day_number": 31,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-02-01T23:00:00.000Z",
                "end": "2018-12-31T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-03-31T08:00:00+00:00', // Fri, 31 Mar 2017 08:00:00 +0000
            '2017-05-31T08:00:00+00:00', // Wed, 31 May 2017 08:00:00 +0000
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2017-08-31T08:00:00+00:00', // Thu, 31 Aug 2017 08:00:00 +0000
            '2017-10-31T09:00:00+00:00', // Tue, 31 Oct 2017 09:00:00 +0000
            '2017-12-31T09:00:00+00:00', // Sun, 31 Dec 2017 09:00:00 +0000
            '2018-01-31T09:00:00+00:00', // Wed, 31 Jan 2018 09:00:00 +0000
            '2018-03-31T08:00:00+00:00', // Sat, 31 Mar 2018 08:00:00 +0000
            '2018-05-31T08:00:00+00:00', // Thu, 31 May 2018 08:00:00 +0000
            '2018-07-31T08:00:00+00:00', // Tue, 31 Jul 2018 08:00:00 +0000
            '2018-08-31T08:00:00+00:00', // Fri, 31 Aug 2018 08:00:00 +0000
            '2018-10-31T09:00:00+00:00', // Wed, 31 Oct 2018 09:00:00 +0000
            '2018-12-31T09:00:00+00:00', // Mon, 31 Dec 2018 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Every month on the 31st excluding specific days of the week",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "day_number": 31,
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
                "start": "2017-01-01T23:00:00.000Z",
                "end": "2018-12-31T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-01-31T09:00:00+00:00', // Tue, 31 Jan 2017 09:00:00 +0000
            '2017-03-31T08:00:00+00:00', // Fri, 31 Mar 2017 08:00:00 +0000
            '2017-05-31T08:00:00+00:00', // Wed, 31 May 2017 08:00:00 +0000
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2017-08-31T08:00:00+00:00', // Thu, 31 Aug 2017 08:00:00 +0000
            '2017-10-31T09:00:00+00:00', // Tue, 31 Oct 2017 09:00:00 +0000
            '2018-01-31T09:00:00+00:00', // Wed, 31 Jan 2018 09:00:00 +0000
            '2018-05-31T08:00:00+00:00', // Thu, 31 May 2018 08:00:00 +0000
            '2018-07-31T08:00:00+00:00', // Tue, 31 Jul 2018 08:00:00 +0000
            '2018-08-31T08:00:00+00:00', // Fri, 31 Aug 2018 08:00:00 +0000
            '2018-10-31T09:00:00+00:00', // Wed, 31 Oct 2018 09:00:00 +0000
            '2018-12-31T09:00:00+00:00', // Mon, 31 Dec 2018 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Last day of each month",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "modifier": "last_day",
            "day_number": 31,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-01-01T00:00:00.000Z",
                "end": "2018-01-01T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-01-31T09:00:00+00:00', // Tue, 31 Jan 2017 09:00:00 +0000
            '2017-02-28T09:00:00+00:00', // Wed, 01 Mar 2017 09:00:00 +0000
            '2017-03-31T08:00:00+00:00', // Thu, 30 Mar 2017 08:00:00 +0000
            '2017-04-30T08:00:00+00:00', // Sun, 30 Apr 2017 08:00:00 +0000
            '2017-05-31T08:00:00+00:00', // Wed, 31 May 2017 08:00:00 +0000
            '2017-06-30T08:00:00+00:00', // Fri, 30 Jun 2017 08:00:00 +0000
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2017-08-31T08:00:00+00:00', // Thu, 31 Aug 2017 08:00:00 +0000
            '2017-09-30T08:00:00+00:00', // Sat, 30 Sep 2017 09:00:00 +0000
            '2017-10-31T09:00:00+00:00', // Tue, 31 Oct 2017 09:00:00 +0000
            '2017-11-30T09:00:00+00:00', // Thu, 30 Nov 2017 09:00:00 +0000
            '2017-12-31T09:00:00+00:00', // Sun, 31 Dec 2017 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Last day of every second month",
            "type": "periodic",
            "interval": "2",
            "period": "months",
            "modifier": "last_day",
            "day_number": 31,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-01-01T00:00:00.000Z",
                "end": "2018-01-01T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-01-31T09:00:00+00:00', // Tue, 31 Jan 2017 09:00:00 +0000
            '2017-03-31T08:00:00+00:00', // Thu, 30 Mar 2017 08:00:00 +0000
            '2017-05-31T08:00:00+00:00', // Wed, 31 May 2017 08:00:00 +0000
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2017-09-30T08:00:00+00:00', // Sat, 30 Sep 2017 09:00:00 +0000
            '2017-11-30T09:00:00+00:00', // Thu, 30 Nov 2017 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "Last working day of each month",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "modifier": "last_working_day",
            "day_number": 31,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-01-01T00:00:00.000Z",
                "end": "2018-01-01T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-01-31T09:00:00+00:00', // Tue, 31 Jan 2017 09:00:00 +0000
            '2017-02-28T09:00:00+00:00', // Wed, 01 Mar 2017 09:00:00 +0000
            '2017-03-31T08:00:00+00:00', // Thu, 30 Mar 2017 08:00:00 +0000
            '2017-04-28T08:00:00+00:00', // Fri, 28 Apr 2017 08:00:00 +0000
            '2017-05-31T08:00:00+00:00', // Wed, 31 May 2017 08:00:00 +0000
            '2017-06-30T08:00:00+00:00', // Fri, 30 Jun 2017 08:00:00 +0000
            '2017-07-31T08:00:00+00:00', // Mon, 31 Jul 2017 08:00:00 +0000
            '2017-08-31T08:00:00+00:00', // Thu, 31 Aug 2017 08:00:00 +0000
            '2017-09-29T08:00:00+00:00', // Fri, 29 Sep 2017 09:00:00 +0000
            '2017-10-31T09:00:00+00:00', // Tue, 31 Oct 2017 09:00:00 +0000
            '2017-11-30T09:00:00+00:00', // Thu, 30 Nov 2017 09:00:00 +0000
            '2017-12-29T09:00:00+00:00', // Fri, 29 Dec 2017 09:00:00 +0000
        ],
    ],
    [
        '{
            "definition_description": "First working day of each month",
            "type": "periodic",
            "interval": "1",
            "period": "months",
            "modifier": "first_working_day",
            "day_number": 31,
            "week_number": false,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {},
            "timezone": "Europe/London",
            "range": {
                "start": "2017-01-01T00:00:00.000Z",
                "end": "2018-01-01T00:00:00.000Z"
            },
            "time_of_day": "09:00",
            "occurrence": "Weekly",
            "callback": "clone"
        }',
        [
            '2017-01-02T09:00:00+00:00', // Mon, 02 Jan 2017 09:00:00 +0000
            '2017-02-01T09:00:00+00:00', // Wed, 01 Feb 2017 09:00:00 +0000
            '2017-03-01T09:00:00+00:00', // Wed, 01 Mar 2017 09:00:00 +0000
            '2017-04-03T08:00:00+00:00', // Mon, 03 Apr 2017 08:00:00 +0000
            '2017-05-01T08:00:00+00:00', // Mon, 01 May 2017 08:00:00 +0000
            '2017-06-01T08:00:00+00:00', // Thu, 01 Jun 2017 08:00:00 +0000
            '2017-07-03T08:00:00+00:00', // Mon, 03 Jul 2017 08:00:00 +0000
            '2017-08-01T08:00:00+00:00', // Tue, 01 Aug 2017 08:00:00 +0000
            '2017-09-01T08:00:00+00:00', // Fri, 01 Sep 2017 08:00:00 +0000
            '2017-10-02T08:00:00+00:00', // Mon, 02 Oct 2017 09:00:00 +0000
            '2017-11-01T09:00:00+00:00', // Wed, 01 Nov 2017 09:00:00 +0000
            '2017-12-01T09:00:00+00:00', // Fri, 01 Dec 2017 09:00:00 +0000
            '2018-01-01T09:00:00+00:00', // Mon, 01 Jan 2018 09:00:00 +0000
        ],
    ],
    'Same week each month [every second monday] (excluding date outside date range)' => [
        '{ "timezone": "Europe/London", "range": { "start": "2018-11-01", "end": "2019-04-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 1, "period": "months", "day_number": false, "week_number": "second", "days": { "mon": true }, "months": {} }',
        [
            '2018-11-12T09:00:00+00:00', // Mon, 11 Sep 2017 08:00:00 +0000
            '2018-12-10T09:00:00+00:00', // Mon, 09 Oct 2017 08:00:00 +0000
            '2019-01-14T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
            '2019-02-11T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
            '2019-03-11T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
            '2019-04-08T08:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
        ],
    ],
    'Same week each month (with two days and excluded month) [every second monday and wednesday] (excluding dates outside date range)' => [
        '{
            "timezone": "Europe/London",
            "range": {
                "start": "2017-08-16",
                "end": "2017-11-30"
            },
            "time_of_day": "09:00",
            "type": "periodic",
            "interval": 1,
            "period": "months",
            "day_number": false,
            "week_number": "second",
            "days": {
                "mon": true,
                "wed": true
            },
            "months": {
                "aug": true,
                "nov": true
            }
        }',
        [
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-13T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
        ],
    ],
    'Same week each month (with two days and excluded month) [every second monday and wednesday]' => [
        '{ "timezone": "Europe/London", "range": { "start": "2017-08-01", "end": "2017-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 1, "period": "months", "day_number": false, "week_number": "second", "days": { "mon": true, "wed": true }, "months": { "aug": true, "nov": true } }',
        [
            '2017-08-09T08:00:00+00:00', // Wed, 09 Aug 2017 08:00:00 +0000
            '2017-08-14T08:00:00+00:00', // Mon, 14 Aug 2017 08:00:00 +0000
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-13T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
        ],
    ],
    'Same week each month (with two days) [every second monday and wednesday] (excluding dates outside date range)' => [
        '{ "timezone": "Europe/London", "range": { "start": "2017-08-16", "end": "2017-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 1, "period": "months", "day_number": false, "week_number": "second", "days": { "mon": true, "wed": true }, "months": {} }',
        [
            '2017-09-11T08:00:00+00:00', // Mon, 11 Sep 2017 08:00:00 +0000
            '2017-09-13T08:00:00+00:00', // Wed, 13 Sep 2017 08:00:00 +0000
            '2017-10-09T08:00:00+00:00', // Mon, 09 Oct 2017 08:00:00 +0000
            '2017-10-11T08:00:00+00:00', // Wed, 11 Oct 2017 08:00:00 +0000
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-13T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
        ],
    ],
    'Same week each month (with two days) [every second monday and wednesday]' => [
        '{ "timezone": "Europe/London", "range": { "start": "2017-08-01", "end": "2017-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 1, "period": "months", "day_number": false, "week_number": "second", "days": { "mon": true, "wed": true }, "months": {} }',
        [
            '2017-08-09T08:00:00+00:00', // Wed, 09 Aug 2017 08:00:00 +0000
            '2017-08-14T08:00:00+00:00', // Mon, 14 Aug 2017 08:00:00 +0000
            '2017-09-11T08:00:00+00:00', // Mon, 11 Sep 2017 08:00:00 +0000
            '2017-09-13T08:00:00+00:00', // Wed, 13 Sep 2017 08:00:00 +0000
            '2017-10-09T08:00:00+00:00', // Mon, 09 Oct 2017 08:00:00 +0000
            '2017-10-11T08:00:00+00:00', // Wed, 11 Oct 2017 08:00:00 +0000
            '2017-11-08T09:00:00+00:00', // Wed, 08 Nov 2017 09:00:00 +0000
            '2017-11-13T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
        ],
    ],
    'Same week each month [every second monday]' => [
        '{ "timezone": "Europe/London", "range": { "start": "2017-08-01", "end": "2017-11-30" }, "time_of_day": "09:00", "type": "periodic", "interval": 1, "period": "months", "day_number": false, "week_number": "second", "days": { "mon": true }, "months": {} }',
        [
            '2017-08-14T08:00:00+00:00', // Mon, 14 Aug 2017 08:00:00 +0000
            '2017-09-11T08:00:00+00:00', // Mon, 11 Sep 2017 08:00:00 +0000
            '2017-10-09T08:00:00+00:00', // Mon, 09 Oct 2017 08:00:00 +0000
            '2017-11-13T09:00:00+00:00', // Mon, 13 Nov 2017 09:00:00 +0000
        ],
    ],
    'Third day of every third month' => [
        '{
            "timezone": "UTC",
            "range": {
                "start": "2018-12-01",
                "end": "2019-12-01"
            },
            "time_of_day": "10:00",
            "type": "periodic",
            "interval": 3,
            "period": "months",
            "day_number": 3,
            "week_number": 0,
            "days": {
                "mon": true,
                "tue": true,
                "wed": true,
                "thu": true,
                "fri": true,
                "sat": true,
                "sun": true
            },
            "months": {
                "jan": true,
                "feb": true,
                "mar": true,
                "apr": true,
                "may": true,
                "jun": true,
                "jul": true,
                "aug": true,
                "sep": true,
                "oct": true,
                "nov": true,
                "dec": true
            }
        }',
        [
            '2018-12-03T10:00:00+00:00', 
            '2019-03-03T10:00:00+00:00', 
            '2019-06-03T10:00:00+00:00', 
            '2019-09-03T10:00:00+00:00', 
        ],
    ],
];