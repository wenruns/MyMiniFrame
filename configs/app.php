<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 9:46
 */
return [
    /**
     * 开启用用调试模式
     */
    'app_debug' => env('APP_DEBUG', false),

    /**
     * 控制器后缀
     */
    'class_suffix' => 'Controller',

    /**
     * 应用目录
     */
    'application' => 'app',

    /**
     * 命名空间配置
     */
    'namespace' => [
        /**
         * 控制器根命名空间
         */
        'root' => '\\app\\controller\\',
        /**
         * 命名映射
         */
        'map' => [
//            'test' => '\\app\\controller\\admin\\'
        ],
    ],
];