<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/12
 * Time: 9:17
 */

namespace app;


use middlewares\ExampleMiddle;
use middlewares\MiddleInterface;

class Bootstrap extends \core\wen\Bootstrap
{

    public function handle()
    {
        $this->register(MiddleInterface::class, ExampleMiddle::class);
    }
}