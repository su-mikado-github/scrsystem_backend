<?php

return [
    'not_found' => [
        'ticket' => '回数券が存在しません。',
        'calendar' => 'カレンダーが存在しません。',
        'month_calender' => 'カレンダーが存在しません。',
        'reserve' => '予約されていません。',
    ],
    'io_error' => [
        'dish_menu_csv_read' => 'メニューCSVファイルの読み込みに失敗しました。',
    ],
    'invalidate' => [
        'upload' => [
            'dish_menu' => 'メニューCSVファイルのアップロードに失敗しました。'
        ],
        'format' => [
            'year_month' => '指定された年月は正しくありません。',
        ],
    ],
    'warning' => [
        'ticket_by_short' => 'チケットが不足しています。',
    ],
    'error' => [
        'reserve_seat_short' => '座席が空いていないため、予約出来ませんでした。',
        'push_messages' => 'LINEへの通知ができませんでした。',
        'ticket_by_short' => 'チケットが不足しています。',
        // 'checkin_token' => 'チェックインの認証情報が不足しています。<br>認証情報を作成するためにマイページを更新してください。<br><br><a class="btn btn-link" href="' . route('mypage') . '">マイページはこちらをタップ</a>',
        'illegal_user' => '不正なチェックインです。',
        'canceled' => '予約は既にキャンセル済みです。',
        'visit_checkin' => '予約は既にチェックイン済みです。',
        'lunchbox_checkin' => 'お弁当は既に受け取り済みです。',
        'cancel_limit' => '前日、または当日の予約のキャンセルはできません。',
        'change_limit' => '前日、または当日の予約の変更はできません。',
        'no_dish_menu' => 'メニューが設定されていません。',
        'reserve_exists' => '既に予約されています。',
        'same_date' => '同じ日付は指定できません。',
        'same_time' => '同じ時刻は指定できません。'
    ],
];
