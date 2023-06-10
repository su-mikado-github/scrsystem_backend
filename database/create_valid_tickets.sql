-- Project Name : 学食予約システム
-- Date/Time    : 2023/06/10 13:52:39
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

-- 有効回数券
drop view if exists `valid_tickets`;

create view `valid_tickets` as 
SELECT
    T.id AS buy_ticket_id -- 購入回数券ID
    , T.user_id -- ユーザーID
    , T.buy_dt -- 購入日時
    , T.ticket_id -- 回数券ID
    , T.ticket_count -- 購入回数券枚数
    , IFNULL(T1.use_ticket_count, 0) AS use_ticket_count -- 利用回数券枚数
    , T.ticket_count - IFNULL(T1.use_ticket_count, 0) AS valid_ticket_count -- 有効回数券枚数
FROM
    buy_tickets T
    LEFT OUTER JOIN (
        SELECT
            ST.buy_ticket_id
            , COUNT(*) AS use_ticket_count
        FROM
            use_tickets ST
        WHERE
            ST.is_delete = 0
        GROUP BY
            ST.buy_ticket_id
    ) T1 ON (
        T1.buy_ticket_id = T.id
    )
WHERE
    T.is_delete = 0

;

