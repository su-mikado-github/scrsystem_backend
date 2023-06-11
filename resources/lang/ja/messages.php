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
    ],
];
