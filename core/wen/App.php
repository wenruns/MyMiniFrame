<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 13:47
 */

namespace core\wen;


class App
{

    protected static $_app = null;

    protected static $instances = [];

    public function __construct()
    {
        self::$_app = $this;
    }


    public static function make($class, $params = [])
    {
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class(...$params);
        }
        return self::$instances[$class];
    }


    public static function addInstance($class, $instance)
    {
        self::$instances[$class] = $instance;
        return self::$_app;
    }


}

