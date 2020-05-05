<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30
 * Time: 14:45
 */
return [
    'APP_DEBUG' => env('APP_DEBUG', false),


    'key' => '123456',
    'xml_save_path' => storage_path('xml'),
    'xml_save_name' => 'xmlFile.xml',
    'ip_list' => [],
    'time_limit' => [
        'start' => '',
        'end' => '',
    ],


    'OSS_ACCESS_ID' => '',
    'OSS_ACCESS_KEY' => '',
    'OSS_ENDPOINT' => '',
    'OSS_BUCKET' => '',
    'OSS_HOST' => '',
    'OSS_URL' => '',
];
