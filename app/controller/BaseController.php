<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/13
 * Time: 13:53
 */

namespace app\controller;


use core\wen\traits\FileTrait;

class BaseController
{
    use FileTrait;

    public function request($index, $default = null)
    {
        return request($index, $default);
    }
}