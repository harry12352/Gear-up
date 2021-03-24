<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
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

    'usps' => [
        'host' => env('USPS_HOST'),
        'client_id' => env('USPS_CLIENT_ID'),
        'rates_api_version' => "RATEV4"
    ],
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_CLIENT_SECRET'),
        'testing' => env('PAYPAL_TESTING', false),
        'cancel_url' => "/cancel-payment",
        'return_url' => "/accept-payment",
    ],
    'fedex' => [
        'key' => 'h4OZB1MfShYEWanp',
        'pass' => '8yhsCTU52uxKdfTyciMQ04qB9',
        'accNo' => '510087780',
        'meterNo' => '119160904'
    ]

];
