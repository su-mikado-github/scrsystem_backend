-- Project Name : 学食予約システム
-- Date/Time    : 2023/06/13 22:09:37
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

-- 週カレンダー
drop view if exists `week_calendars`;

create view `week_calendars` as 
select
  T.id -- カレンダーID
  , T.date -- 日付
  , T.year -- 年
  , T.month -- 月
  , T.day -- 日
  , T.weekday -- 曜日
  , T.week_of_month -- 曜日月内回数
  , T.week_of_year -- 曜日年内回数
  , T.is_holiday -- 祝日フラグ
  , T.holiday_name -- 祝日名
  , (
    select
      MAX(ST1.date)
    from
      calendars ST1
    where
      ST1.date >= DATE_SUB(T.date, interval 6 day)
      and ST1.weekday = 7
      and ST1.date <= T.date
  ) as week_start_date -- 週日曜日日付
  , (
    select
      MIN(ST2.date)
    from
      calendars ST2
    where
      ST2.date <= DATE_ADD(T.date, interval 6 day)
      and ST2.weekday = 6
  ) as week_end_date -- 週土曜日日付
from
  calendars T

;

