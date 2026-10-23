<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Credentials for third party services. Values an administrator can change
    | from the admin panel live in the database settings tables instead, so
    | everything here is deployment level only.
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

    /*
    |--------------------------------------------------------------------------
    | SMS Gateway
    |--------------------------------------------------------------------------
    |
    | Deployment level fallback only. The voice provider credentials an
    | administrator sets from the settings screen live in
    | su_voice_provider_credentials instead.
    |
    */

    'sms' => [
        'provider'  => env('SMS_PROVIDER', 'africastalking'),
        'api_key'   => env('SMS_API_KEY'),
        'username'  => env('SMS_USERNAME'),
        'sender_id' => env('SMS_SENDER_ID'),
    ],

];
