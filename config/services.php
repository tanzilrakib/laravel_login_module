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
    'guzzle' => ['verify' => false] ,

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    // 'facebook' => [
    //     'client_id' => env('FB_CLIENT_ID'),
    //     'client_secret' => env('FB_CLIENT_SECRET'),
    //     'redirect' => env('FB_REDIRECT'),
    // ],

    'facebook' => [
        'client_id' => '128805704456189',
        'client_secret' => 'f621343de20b68e611fc97f593b44538',
        'redirect' => 'http://localhost:8000/login/facebook/callback',
    ],

];
