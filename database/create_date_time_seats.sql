-- Project Name : 学食予約システム
-- Date/Time    : 2023/06/01 11:52:20
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

-- 日付時刻別座席
drop view if exists `date_time_seats`;

create view `date_time_seats` as 
SELECT
    T.id AS seat_id -- 座席ID
    , T.seat_no -- 座席番号
    , T.seat_group_no -- 座席グループ番号
    , T1.id AS calendar_id -- カレンダーID
    , T1.date -- 日付
    , T1.year -- 年
    , T1.month -- 月
    , T1.day -- 日
    , T1.weekday -- 曜日
    , T2.id AS time_id -- 時刻ID
    , T2.hour -- 時
    , T2.minute -- 分
    , T2.time -- 時刻
    , T2.time_minutes -- 時刻(分)
FROM
    seats T 
    INNER JOIN calendars T1 ON (
        T1.is_delete = T.is_delete
    )
    INNER JOIN times T2 ON (
        T2.is_delete = T.is_delete
    )


;

