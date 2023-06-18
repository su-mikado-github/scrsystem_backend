<?php

return [
    'not_found' => [
        'ticket' => '回数券が存在しません。',
        'calendar' => 'カレンダーが存在しません。',
        'month_calender' => 'カレンダーが存在しません。',
        'reserve' => '予約されていません。',
        'user' => '指定された利用者は、登録されていないか既に退会しています。',
        'staff' => '指定された管理者は、存在しません。',
    ],
    'io_error' => [
        'dish_menu_csv_read' => 'メニューCSVファイルの読み込みに失敗しました。',
        'users_csv_write' => '利用者CSVファイルの作成に失敗しました。',
    ],
    'invalidate' => [
        'upload' => [
            'dish_menu' => 'メニューCSVファイルのアップロードに失敗しました。'
        ],
        'format' => [
            'year_month' => '指定された年月は正しくありません。',
        ],
        'email' => 'ご指定のメールアドレスは登録されていません。',
    ],
    'warning' => [
        'ticket_by_short' => 'チケットが不足しています。',
        'no_initialize' => 'マイページで利用者情報を登録されていません。',
    ],
    'error' => [
        'not_line_follow' => 'CLUBHOUSE公式アカウントを友達に追加してください。',
        'follow_retry' => 'システムに問題が発生しました。',
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
        'same_time' => '同じ時刻は指定できません。',
    ],
    'success' => [
        'user' => [
            'delete' => '利用者を削除しました。',
        ],
        'staff' => [
            'post' => '管理者を登録しました。',
            'put' => '管理者を保存しました。',
            'delete' => '管理者を削除しました。',
        ],
        'account' => [
            'put' => 'アカウント情報を変更しました。',
        ],
        'dish_menu' => [
            'put' => '料理メニューを更新しました。',
        ],
    ],
    'subject' => [
        'create_staff' => '[CLUBHOUSE] 管理者として登録されました',
        'password_reset' => '[CLUBHOUSE] パスワードがリセットされました',
        'password_change' => '[CLUBHOUSE] パスワードを変更しました。',
    ],
];
