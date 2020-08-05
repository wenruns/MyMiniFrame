<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/12
 * Time: 9:16
 */
return [
    'default' => 'mysql',

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'username' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'dbName' => 'test',
            'port' => '3306',
            'charset' => 'utf8',
            'collate' => 'utf8_general_ci',
            'engine' => null,
            'prefix' => '',
            'schema' => '',
            'unixSocket' => '',
            'options' => [],
        ],
        'old_mysql' => [
            'driver' => 'mysql',
            'username' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'database' => 'signature',
            'port' => '3306',
            'charset' => '',
            'prefix' => '',
            'schema' => '',
            'ssl_mode' => [],
        ]
    ]
];