/**
 *  空き状況（empty_states）の再構築
 */
CREATE PROCEDURE rebuild_empty_states(IN pv_date DATE, IN pv_start_time TIME, IN pv_end_time TIME)
BEGIN
    -- 元データは削除
    DELETE
        FROM
            empty_states
        WHERE 
            date = pv_date
            AND time BETWEEN pv_start_time AND pv_end_time
    ;
    
    -- データ生成
    INSERT
        INTO empty_states (
            calendar_id
            , date
            , year
            , month
            , day
            , weekday
            , time_id
            , time
            , hour
            , minute
            , time_minutes
            , seat_count
            , empty_seat_count
            , empty_seat_rate
            , created_at
            , created_id
            , updated_at
            , updated_id
        )
        SELECT
            T.calendar_id
            , T.date
            , T.year
            , T.month
            , T.day
            , T.weekday
            , T.time_id
            , T.time
            , T.hour
            , T.minute
            , T.time_minutes
            , T.seat_count
            , T.empty_seat_count
            , CEIL(T.empty_seat_count * 100 / T.seat_count) AS empty_seat_rate
            , CURRENT_TIMESTAMP
            , 0
            , CURRENT_TIMESTAMP
            , 0
        FROM
            (
                SELECT
                    T.calendar_id
                    , T.date
                    , T.year
                    , T.month
                    , T.day
                    , T.weekday
                    , T.time_id
                    , T.time
                    , T.hour
                    , T.minute
                    , T.time_minutes
                    , COUNT(T.seat_id) AS seat_count
                    , (SELECT COUNT(*) FROM empty_seats WHERE calendar_id=T.calendar_id AND time_id=T.time_id) AS empty_seat_count
                FROM
                    date_time_seats T
                WHERE
                    T.date = pv_date
                    AND time BETWEEN pv_start_time AND pv_end_time
                GROUP BY
                    T.calendar_id
                    , T.date
                    , T.year
                    , T.month
                    , T.day
                    , T.weekday
                    , T.time_id
                    , T.time
                    , T.hour
                    , T.minute
                    , T.time_minutes
            ) T
        ORDER BY
            T.date
            , T.time
    ;
END

