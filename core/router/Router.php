<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 16:53
 */

class Router
{
    public static $prefix = '';

    protected static $router = null;

    protected static function getRouter()
    {
        if (empty(self::$router)) {
            self::$router = new RouterDriver();
        }
        return self::$router;
    }

    public static function post($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'POST');
    }

    public static function get($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'GET');
    }

    public static function delete($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'DELETE');
    }

    public static function put($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'PUT');
    }

    public static function head($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'HEAD');
    }

    public static function options($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'OPTIONS');
    }

    public static function connect($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'CONNECTION');
    }


    public static function trace($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'TRACE');
    }

    public static function any($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'ANY');
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        self::getRouter()->$name($arguments);
    }
}

function loading($dir_path, $prefix = '')
{
    foreach (scandir($dir_path) as $file) {
        if ($file != '.' && $file != '..') {
            if (is_dir($dir_path . DS . $file)) {
                loading($dir_path . DS . $file, $prefix . '/' . $file);
            } else if (is_file($dir_path . DS . $file)) {
                Router::$prefix = $prefix . '/' . substr($file, 0, strpos($file, '.'));
                include_once $dir_path . DS . $file;
            }
        }
    }
}

loading(ROUTER_PATH);
require_once __DIR__ . DS . 'RouterMethod.php';
Router::run();







