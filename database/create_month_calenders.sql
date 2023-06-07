-- Project Name : 学食予約システム
-- Date/Time    : 2023/05/21 23:50:57
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
drop view if exists month_calendars;

create view month_calendars as 
SELECT
    T.year -- 年
    , T.month -- 月
    , T.day -- 月初日
    , T.date -- 月初日付
    , MAX(T1.date) AS last_date -- 月末日
    , MAX(T1.day) AS last_day -- 月末日付
    , T2.year AS previous_year -- 前月年
    , T2.month AS previous_month -- 前月月
    , T2.day AS previous_day -- 前月月初日
    , T2.date AS previous_date -- 前月月初日付
    , T3.year AS next_year -- 次月年
    , T3.month AS next_month -- 次月月
    , T3.day AS next_day -- 次月月初日
    , T3.date AS next_date -- 次月月初日付
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

;

