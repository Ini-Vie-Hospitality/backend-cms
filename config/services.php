<?php

return [
    'copilot' => [
        'text_provider' => env('COPILOT_TEXT_PROVIDER', env('CONCIERGE_TEXT_PROVIDER', 'deepseek')),
        'text_model' => env('COPILOT_TEXT_MODEL', env('CONCIERGE_DEEPSEEK_MODEL', 'deepseek-v4-flash')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'homepage' => [
        'frontend_url' => env('FRONTEND_WEB_URL', 'http://localhost:3000'),
        'preview_secret' => env('HOMEPAGE_PREVIEW_SECRET'),
        'revalidate_url' => env('FRONTEND_REVALIDATE_URL'),
        'revalidate_secret' => env('FRONTEND_REVALIDATE_SECRET'),
    ],

];
