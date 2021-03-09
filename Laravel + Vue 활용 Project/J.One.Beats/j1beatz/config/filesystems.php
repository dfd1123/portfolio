<?php

return [

    'beat_mp3'=> 'audio/beat/mp3/', // `/file/temp/${임시주소} /file/down/${확장자}/${주문번호}
    'beat_wav'=>  'audio/beat/wav/', // `/file/temp/${임시주소} /file/down/${확장자}/${주문번호}

    'beat_clip'=> 'audio/beat/clip/', // /fdata/clip/
    'user_thumb'=> 'img/user/thumb/', // /fdata/usrthumb/
    'maker_thumb'=> 'img/maker/thumb/', // /fdata/mkrthumb/
    'banner_thumb'=> 'img/system/banner/', // /fdata/banner/
    'mood_thumb'=> 'img/system/mood/', // /fdata/mood/
    'beat_thumb'=> 'img/beat/thumb/', // /fdata/beathumb/
    'maker_sample'=> 'audio/maker/sample/', // /fdata/mkrsmpl/

    // /fdata/clip "D:\home\j1beatz\storage\audio\beat\clip"
    // /fdata/usrthumb "D:\home\j1beatz\storage\img\user\thumb"
    // /fdata/mkrthumb "D:\home\j1beatz\storage\img\maker\thumb"
    // /fdata/banner "D:\home\j1beatz\storage\img\system\banner"
    // /fdata/mood "D:\home\j1beatz\storage\img\system\mood"
    // /fdata/beathumb "D:\home\j1beatz\storage\img\beat\thumb"
    // /fdata/mkrsmpl "D:\home\j1beatz\storage\audio\maker\sample"

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
            'root' => '/home/j1beatz/storage/'
        ]

        ,
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'file' => [
            'driver' => 'local',
            'root' => storage_path('app/file'),
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
