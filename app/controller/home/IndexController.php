<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/13
 * Time: 14:44
 */

namespace app\controller\home;


use app\controller\BaseController;
use app\models\Users;
use core\wen\models\Query;
use http\Client\Curl\User;

class IndexController extends BaseController
{
    public function index()
    {
        $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle($arr);
        $data = [
            [
//            'id' => '2', // bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                'name' => 'wen', // varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                'email' => '123456@qq.com', // varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                'email_verified_at' => date('Y-m-d H:i:s'), // timestamp NULL DEFAULT NULL,
                'password' => md5(123456), // varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                'remember_token' => mb_substr(implode('', $arr), mt_rand(0, count($arr) - 1), 64), // varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                'created_at' => date('Y-m-d H:i:s'), // timestamp NULL DEFAULT NULL,
                'updated_at' => date('Y-m-d H:i:s'), // timestamp NULL DEFAULT NULL,
            ], [
//            'id' => '2', // bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                'name' => 'wen1', // varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                'email' => '123456@qq.com', // varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                'email_verified_at' => date('Y-m-d H:i:s'), // timestamp NULL DEFAULT NULL,
                'password' => md5(123456), // varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                'remember_token' => mb_substr(implode('', $arr), mt_rand(0, count($arr) - 1), 64), // varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                'created_at' => date('Y-m-d H:i:s'), // timestamp NULL DEFAULT NULL,
                'updated_at' => date('Y-m-d H:i:s'), // timestamp NULL DEFAULT NULL,
            ],
        ];
        $res = Users::save($data);
        if ($errorInfo = $res->getErrorInfo()) {
            dd($errorInfo);
        }

        $info = Users::alias('u')
//            ->leftJoin('admin_role_users as r', 'r.user_id', '=', 'u.id')
//            ->whereNull('u.deleted_at')
//            ->groupBy('u.id')
            ->orderBy('u.id', 'desc')
            ->get(['name', 'id']);

        dd(111, $info->toArray());
        return view('admin.index.index');
    }
}