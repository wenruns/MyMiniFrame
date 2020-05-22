<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 14:07
 */

namespace core\wen\request;


class Post extends AbstractRequest
{
    public function data()
    {
        // TODO: Implement data() method.
        return $_POST;
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        unset($_POST);
    }

}