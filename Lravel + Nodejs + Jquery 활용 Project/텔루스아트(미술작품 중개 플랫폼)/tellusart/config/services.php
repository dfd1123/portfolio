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

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => TLCfund\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    
	'github' => [
        'client_id' => '14813f7e2b57cbaa8a82',
        'client_secret' => '4b8c293a0a38994e971d7ef58d25f911952f06d6',
        'redirect' => 'http://fund3.rstotal.com/github/callback',
    ],
    
	'naver' => [
	    'client_id' => 'WQdSrJPxnAaseI2QGxvN',
	    'client_secret' => '643pLdRrr5',
	    'redirect' => 'http://fund3.rstotal.com/naver/callback',
	],
	
	'kakao' => [
	    'client_id' => '0dcdf0bf1fd55cd4c45b2c8cc9378b04',
	    'client_secret' => 'TZE3DQsAqIOL6ShjDfKWNqBqMq53uj3r',
	    'redirect' => 'http://fund3.rstotal.com/kakao/callback',
	],

];
