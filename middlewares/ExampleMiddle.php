<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 17:25
 */

namespace middlewares;


class ExampleMiddle implements MiddleInterface
{
    public function __construct($id)
    {
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        echo 'this is middleware.';
    }
}