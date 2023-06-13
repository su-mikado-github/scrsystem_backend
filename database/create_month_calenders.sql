-- Project Name : 学食予約システム
-- Date/Time    : 2023/06/13 22:09:58
-- Author       : Shuji Ushiyama
-- RDBMS Type   : MySQL
-- Application  : A5:SQL Mk-2

/*
  << 注意！！ >>
  BackupToTempTable, RestoreFromTempTable疑似命令が付加されています。
  これにより、drop table, create table 後もデータが残ります。
  この機能は一時的に $$TableName のような一時テーブルを作成します。
  この機能は A5:SQL Mk-2でのみ有効であることに注意してください。
*/

-- 月カレンダー
drop view if exists `month_calendars`;

create view `month_calendars` as 
SELECT
    T.year -- 年
    , T.month -- 月
    , T.day -- 月初日
    , T.date -- 月初日付
    , T.last_date -- 月末日
    , T.last_day -- 月末日付
    , T.previous_year -- 前月年
    , T.previous_month -- 前月月
    , T.previous_day -- 前月月初日
    , T.previous_date -- 前月月初日付
    , T.next_year -- 次月年
    , T.next_month -- 次月月
    , T.next_day -- 次月月初日
    , T.next_date -- 次月月初日付
    , (
        SELECT MAX(ST.date) FROM calendars ST WHERE ST.date <= T.date AND ST.weekday = 7
      ) AS start_date -- 当月を含む初週の開始日
    , (
        SELECT MIN(ST.date) FROM calendars ST WHERE ST.date >= T.last_date AND ST.weekday = 6
      ) AS end_date -- 当月を含む末週の終了日
FROM
    (
        SELECT
            T.year
            , T.month
            , T.day
            , T.date
            , MAX(T1.date) AS last_date
            , MAX(T1.day) AS last_day
            , T2.year AS previous_year
            , T2.month AS previous_month
            , T2.day AS previous_day
            , T2.date AS previous_date
            , T3.year AS next_year
            , T3.month AS next_month
            , T3.day AS next_day
            , T3.date AS next_date
        FROM
            calendars T 
            INNER JOIN calendars T1 ON (
                T1.year = T.year 
                AND T1.month = T.month 
            )
            INNER JOIN calendars T2 ON (
                T2.date = SubDate(T.date, INTERVAL 1 MONTH) 
            )
            INNER JOIN calendars T3 ON (
                T3.date = AddDate(T.date, INTERVAL 1 MONTH) 
            )
        WHERE
            T.day = 1 
        GROUP BY
            T.year
            , T.month
            , T.day
            , T.date
            , T2.year
            , T2.month
            , T2.day
            , T2.date
            , T3.year
            , T3.month
            , T3.day
            , T3.date 
    ) T


;

