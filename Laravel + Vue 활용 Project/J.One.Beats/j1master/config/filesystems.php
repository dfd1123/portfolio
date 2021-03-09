<?php

define('STORAGE_ROOT','/home/j1beatz/storage/' );

return [


    //'storage_path'=> STORAGE_ROOT.'',
    'storage_path'=> '/home/j1beatz/storage/',
    
	'beat_mp3'=> 'audio/beat/mp3/',
	'beat_wav'=>  'audio/beat/wav/',
	'user_thumb'=> 'img/user/thumb/',
	'maker_thumb'=> 'img/maker/thumb/',
	'banner_thumb'=> 'img/system/banner/',
	'mood_thumb'=> 'img/system/mood/',

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */


    //'default' => env('FILESYSTEM_DRIVER', 'local'),
    'default' => env('FILESYSTEM_DRIVER', 'shared'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [


        'shared'=>[
            'driver' =>'local',
            'root' => STORAGE_ROOT.''
        ]

        ,
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

    ],

];
