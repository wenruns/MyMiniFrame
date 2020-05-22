<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 16:53
 */

namespace core\facades;


use core\wen\App;

/**
 * Class Router
 * @package core\router
 */
class Route
{
    public static $prefix = '';

    public static $middleware = null;

    public static $namespace = '';

    public static $route_prefix = '';


    protected static $_route = null;


    protected static function getRoute()
    {
        if (empty(self::$_route)) {
//            self::$_route = new \core\router\Route();
            self::$_route = App::make(\core\router\Route::class);
        }
        return self::$_route;
    }


    public static function post($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'POST', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function get($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'GET', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function delete($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'DELETE', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function put($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'PUT', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function head($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'HEAD', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function options($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'OPTIONS', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function connect($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'CONNECTION', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }


    public static function trace($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'TRACE', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }

    public static function any($uri, $closure)
    {
        self::getRoute()->getRouteDriver()->routeParsing($uri, $closure, 'ANY', self::$prefix, self::$middleware, self::$namespace, self::$route_prefix);
        return self::getRoute();
    }


    /**
     * @param array $options
     * @param \Closure $closure
     * @return Route|null
     */
    public static function group(array $options, \Closure $closure)
    {
        self::$middleware = isset($options['middleware']) ? $options['middleware'] : null;
        self::$namespace = isset($options['namespace']) ? $options['namespace'] : '';
        self::$route_prefix = isset($options['prefix']) ? $options['prefix'] : '';
        call_user_func($closure, self::getRoute());
        self::$middleware = null;
        self::$namespace = '';
        self::$route_prefix = '';
        return self::getRoute();
    }

}








