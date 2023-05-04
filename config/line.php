<?php

return [
    'oauth' => [
        'url' => env('LINE_OAUTH_URL', null),
        'client_id' => env('LINE_OAUTH_CLIENT_ID', null),
        'client_secret' => env('LINE_OAUTH_CLIENT_SECRET', null),
        'redirect_uri' => env('LINE_OAUTH_REDIRECT_URI', null),
    ],

    'message' => [
        'client_id' => env('LINE_MESSAGE_CLIENT_ID', null),
        'client_secret' => env('LINE_MESSAGE_CLIENT_SECRET', null),
    ],

    'api' => [
        'check_access_token' => [
            'url' => 'https://api.line.me/v2/oauth/verify',
            'method' => 'POST',
            'headers' => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ],

        'get_access_token' => [
            'url' => 'https://api.line.me/v2/oauth/accessToken',
            'method' => 'POST',
            'headers' => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ],

        'refresh_access_token' => [
            'url' => 'https://api.line.me/v2/oauth/accessToken',
            'method' => 'POST',
            'headers' => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ],

        'get_profile' => [
            'url' => 'https://api.line.me/v2/profile',
            'method' => 'POST',
        ],

        'push_message' => [
            'url' => 'https://api.line.me/v2/bot/message/push',
            'method' => 'POST',
            'headers' => [
                'Authorization: Bearer ' . env('LINE_MESSAGE_CHANNEL_TOKEN', null),
                'Content-Type: application/json',
            ],
        ],
    ],
];
