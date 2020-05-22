<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 14:09
 */

namespace core\wen\request;


class Server extends AbstractRequest
{
    public function data()
    {
        // TODO: Implement data() method.
        return $_SERVER;
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        unset($_SERVER);
    }

}