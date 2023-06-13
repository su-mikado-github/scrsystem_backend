-- Project Name : 学食予約システム
-- Date/Time    : 2023/06/13 22:08:10
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

-- 日別料理メニュー
drop view if exists `daily_dish_menus`;

create view `daily_dish_menus` as 
SELECT
    T.calendar_id -- カレンダーID
    , T1.date -- 日付
    , T1.year -- 年
    , T1.month -- 月
    , T1.day -- 日
    , T.dish_type -- 料理種別
    , GROUP_CONCAT(T.name ORDER BY T.display_order SEPARATOR '/') as name -- 料理メニュー名
    , SUM(T.energy) AS energy -- エネルギー
    , SUM(T.carbohydrates) AS carbohydrates -- 炭水化物
    , SUM(T.protein) AS protein -- たんぱく質
    , SUM(T.lipid) AS lipid -- 脂質
    , SUM(T.dietary_fiber) AS dietary_fiber -- 食物繊維
FROM
    dish_menus T
    INNER JOIN calendars T1 ON (
        T1.id = T.calendar_id
        AND T1.is_delete = 0
    )
WHERE
    T.is_delete = 0
GROUP BY
    T.calendar_id
    , T.dish_type

;

