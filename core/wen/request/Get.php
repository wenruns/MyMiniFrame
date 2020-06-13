<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 14:07
 */

namespace core\wen\request;


class Get extends AbstractRequest
{
    public function data()
    {
        // TODO: Implement data() method.
        unset($_GET['m']);
        return $_GET;
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        unset($_GET);
    }
}