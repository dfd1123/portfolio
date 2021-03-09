<?php

return [


    //KJS추가

    //유저 썸네일
    'user_thumb' =>     'public/fdata/user/thumb',
    //코멘트 사진
    'comment_photo' =>  'public/fdata/trade/comment',
    //리뷰 사진
    'review_photo' =>  'public/fdata/trade/review',
    //배너사진
    'banner_photo' =>   'public/fdata/system/banner',
    //업종사진
    'blist_photo' =>    'public/fdata/system/blist',
    //리뷰사진
    'rv_photo' =>       'public/fdata/system/review',
    //계약서
    'contract'=>        'public/fdata/system/contract',
    //도면
    'draw'=>            'public/fdata/trade/draw',
    //견적요청 사진
    'est_req_photo' =>  'public/fdata/trade/estimate',
    //업체 프로필 사진
    'agent_thumb' =>     'public/fdata/agent/thumb',
    //업체 사업자등록증 사진
    'agent_business' =>     'public/fdata/agent/business',
    //업체 시공 포트폴리오 사진
    'agent_construct_popol' =>     'public/fdata/agent/con_popol',

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

    'default' => env('FILESYSTEM_DRIVER', 'local'),

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
