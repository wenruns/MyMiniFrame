<?php
/**
 * 应用管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 13:47
 */

namespace core\wen;


use mysql_xdevapi\Exception;

class App
{
    /**
     * @var App|null
     */
    protected static $app = null;

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @var Bootstrap|null
     */
    protected static $bootstrap = null;

    /**
     * App constructor.
     */
    public function __construct()
    {
        self::$app = $this;
        self::$bootstrap = new Bootstrap();
        self::$bootstrap->handle();
    }


    /**
     * @param $class
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public static function make($class, $params = [])
    {
        if (gettype($class) == 'object') {
            return $class;
        }
        if (!isset(self::$instances[$class])) {
            $className = self::checkClassName($class);
            if (empty($className)) {
                throw new \Exception($class . '的映射类不能为空');
            }
            self::$instances[$class] = new $className(...$params);
        }
        return self::$instances[$class];
    }

    /**
     * @param $class
     * @return mixed
     */
    public static function checkClassName($class)
    {
        return self::$bootstrap->getInstanceClass($class);
    }

    /**
     * @param $class
     * @param $instance
     * @return App|null
     */
    public static function addInstance($class, $instance)
    {
        self::$instances[$class] = $instance;
        return self::$app;
    }

    /**
     * @return Bootstrap|null
     */
    public static function bootstrap()
    {
        return self::$bootstrap;
    }
}

