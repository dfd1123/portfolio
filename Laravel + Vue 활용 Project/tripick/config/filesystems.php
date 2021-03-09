<?php

return [


    //추천여행정보
    'trip_tip'    =>    'fdata/system/triptip/',
    //유저 썸네일
    'user_thumb'    =>    'fdata/user/thumb/',
    //플래너 썸네일
    'planner_thumb' =>     'fdata/planner/thumb/',
    //플래너 배경화면
    'planner_bg' =>     'fdata/planner/bg/',
    //플래너 신분증
    'planner_idcard' =>     'fdata/planner/idcard/',
    //플래너 서류
    'planner_docs' =>     'fdata/planner/docs/',
    //플래너 포트폴리오 (영상포함)
    'planner_portfolio' =>     'fdata/planner/portf/',
    /* 필요없음, 슬라이드의 첫번쨰꺼 사용
    //플래너 상품
    'product_thumb' =>     'fdata/product/thumb/',
    */
    //플래너 상품 슬라이드
    'product_slides' =>     'fdata/product/slides/',
    //리뷰
    'review_planner' =>     'fdata/review/planner/',

    'review_product' =>     'fdata/review/product/',
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
            'root' => '/home/tripick/storage/'
        ],

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
