<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
     */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'facebook' => [
        'client_id' => env('APP_ENV') == 'live' ? '' : '643111566214215',
        'client_secret' => env('APP_ENV') == 'live' ? '' : 'ab30781433cbe36cadb613e6f4d632fe',
        'redirect' => env('APP_ENV') == 'live' ? '' : 'https://staging.hapity.com/login/facebook/callback',
    ],

    'twitter' => [
        'client_id' => env('APP_ENV') == 'local' ? env('DEV_TWITTER_CLIENT_ID') : env('LIVE_TWITTER_CLIENT_ID'),
        'client_secret' => env('APP_ENV') == 'local' ? env('DEV_TWITTER_CLIENT_SECRET') : env('LIVE_TWITTER_CLIENT_SECRET'),
        'redirect' => '/login/twitter/callback',
    ],

];
