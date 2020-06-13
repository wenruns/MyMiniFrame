<?php
/**
 * 路由门面
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
 *
 * @method static post($uri, $closure)
 *
 * @method static get($uri, $closure)
 *
 * @method static delete($uri, $closure)
 *
 * @method static put($uri, $closure)
 *
 * @method static head($uri, $closure)
 *
 * @method static options($uri, $closure)
 *
 * @method static trace($uri, $closure)
 *
 * @method static any($uri, $closure)
 *
 * @method static connect($uri, $closure)
 *
 */
class Route
{
    /**
     * @var string
     */
    public static $routeName = '';

    /**
     * @var null
     */
    public static $middleware = null;

    /**
     * @var string
     */
    public static $namespace = '';

    /**
     * @var string
     */
    public static $route_prefix = '';

    /**
     * @var \core\router\Route
     */
    protected static $_route = null;


    /**
     * @return \core\router\Route
     * @throws \Exception
     */
    protected static function getRoute()
    {
        if (empty(self::$_route)) {
            self::$_route = App::make(\core\router\Route::class);
        }
        return self::$_route;
    }

    /**
     * @param array $options
     * @param \Closure $closure
     * @return \core\router\Route
     * @throws \Exception
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
        return self::getRoute()->initParentOptions();
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return self::getRoute()->$name(...$arguments);
    }
}








