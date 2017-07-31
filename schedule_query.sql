
#SET CONVERT_TZ(@current_date, 'UTC', schedules.timezone)=NOW();
SET @current_date=DATE_ADD(NOW(), INTERVAL 3 DAY);
-- SELECT @current_date;
SELECT Date_format(CONVERT_TZ(@current_date, 'UTC', schedules.timezone), '%Y %c %w')
       AS
       current_formatted_day,
       Date_format(CONVERT_TZ(@current_date, 'UTC', schedules.timezone), Concat('%Y ', months.month_number, ' ',
                          days.day_number)) AS
       schedule_formatted_day,
       months.month_name,
       months.month_number,
       days.day_name,
       days.day_number,
       schedules.*
/*        ;
SELECT schedules.id */
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
WHERE 1=1
#AND schedules.id BETWEEN 2700 AND 2710
AND deleted_at IS NULL
AND `status` = 'active'
AND (schedules.starts_at IS NULL || CONVERT_TZ(@current_date, 'UTC', schedules.timezone) >= schedules.starts_at )
AND Date_format(CONVERT_TZ(@current_date, 'UTC', schedules.timezone), '%Y %c %w') = Date_format(CONVERT_TZ(@current_date, 'UTC', schedules.timezone), Concat('%Y ', months.month_number, ' ', days.day_number))
AND (`repeat` = 0 || `run_count` < `repeat`)
AND (( schedules.period = 'every'
|| ( `period` = CASE
    WHEN Ceil(DAY(
    CONVERT_TZ(@current_date, 'UTC', schedules.timezone)) / 7) = 1
     THEN
    'first'
    WHEN Ceil(DAY(
    CONVERT_TZ(@current_date, 'UTC', schedules.timezone)) / 7) = 2
     THEN
    'second'
    WHEN Ceil(DAY(
    CONVERT_TZ(@current_date, 'UTC', schedules.timezone)) / 7) = 3
     THEN
    'thrird'
    WHEN Ceil(DAY(
    CONVERT_TZ(@current_date, 'UTC', schedules.timezone)) / 7) = 4
     THEN
    'fourth'
    WHEN Ceil(DAY(
    CONVERT_TZ(@current_date, 'UTC', schedules.timezone)) / 7) = 4
     THEN
    'fifth'
    ELSE 'every'
  END )
  || (schedules.period = 'alternate' AND MOD(DAYOFYEAR(CONVERT_TZ(@current_date, 'UTC', schedules.timezone)),2) = MOD(DAYOFYEAR(schedules.starts_at),2)
)))


GROUP BY schedules.id
;

/* SELECT * FROM schedules WHERE id IN (1,5,11,18,23,30,33,36,39,40,53,54,55,68,83,93,95,127,144,145,166,212,220,221,222,223,224,255,256,259,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,279,292,376,393,394,403,404,417,418,420,424,516,528,529,530,535,536,537,548,554,555,556,557,558,559,560,561,562,563,564,565,566,567,584,585,586,587,588,589,617,618,619,620,621,622,625,662,758,759,760,761,790,864,865,904,912,919,948,949,950,964,965,967,977,985,1022,1051,1060,1066,1128,1130,1142,1180,1217,1218,1297,1392,1442,1480,1487,1489,1533,1553,1569,1617,1618,1619,1620,1621,1622,1636,1647,1648,1654,1697,1700,1705,1712,1715,1742,1748,1749,1750,1751,1752,1755,1756,1758,1775,1782,1783,1784,1785,1803,1813,1831,1852,1858,1859,1895,1905,1923,1959,1986,2066,2083,2099,2101,2116,2117,2120,2127,2224,2225,2226,2227,2267,2334,2395,2398,2405,2406,2407,2408,2541,2543,2544,2621,2638,2639,2640,2641,2642,2651,2652,2653,2673,2677,2715,2719,2720,2726,2728,2739,2812,1951,1952,1953,1954,1955,1956,1867,1882,2703) */

