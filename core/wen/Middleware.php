<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 16:58
 */

namespace core\wen;


use core\wen\exception\MiddleException;
use middlewares\MiddleInterface;

abstract class Middleware
{
    abstract public function register();


    public function run($name)
    {
        $middles = $this->register();

        if (isset($middles[$name])) {
            $middles = $middles[$name];
            if (is_array($middles)) {
                foreach ($middles as $middle) {
                    $this->resolve($middle)->handle();
                }
            } else {
                $this->resolve($middles)->handle();
            }
            return;
        }

        try {
            $this->resolve($name)->handle();
            return;
        } catch (\Exception $e) {
        }

        throw new MiddleException('不存在中间件');
    }


    /**
     * @param $middle
     * @return MiddleInterface
     */
    protected function resolve($middle)
    {
        return App::make($middle);
    }

}