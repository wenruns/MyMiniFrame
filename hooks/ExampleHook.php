<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 15:25
 */

namespace hooks;


class ExampleHook extends AbstractHook
{

    public function handle()
    {
//        list($action) = func_get_args();
//        dump($action);
        // TODO: Implement handle() method.
        echo 'This is an example for hooks.<hr/>';
    }
}