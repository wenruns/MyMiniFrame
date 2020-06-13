<?php
/**
 * 路由解析
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/21
 * Time: 11:24
 */

namespace core\router;

use core\facades\Route as RouteFacade;
use core\wen\App;

/**
 * Class Route
 * @package core\router
 *
 * @method name($name)
 *
 * @method post($uri, $closure)
 *
 * @method get($uri, $closure)
 *
 * @method delete($uri, $closure)
 *
 * @method put($uri, $closure)
 *
 * @method head($uri, $closure)
 *
 * @method options($uri, $closure)
 *
 * @method trace($uri, $closure)
 *
 * @method any($uri, $closure)
 *
 * @method connect($uri, $closure)
 */
class Route
{
    protected $_route_driver = null;

    protected $_namespace = '';

    protected $_middleware = null;

    protected $_prefix = '';


    /**
     * 解析路由
     * @param $method
     * @param $args
     * @return RouterDriver
     * @throws \Exception
     */
    protected function route($method, $args)
    {
        $this->checkParentOptions();
        $routeName = RouteFacade::$routeName;
        return $this->getRouteDriver()->routeParsing($args[0], $args[1], $method, $routeName, $this->_middleware, $this->_namespace, $this->_prefix);
    }

    /**
     * 获取路由驱动
     * @return RouterDriver
     * @throws \Exception
     */
    protected function getRouteDriver()
    {
        if (empty($this->_route_driver)) {
//            $this->_route_driver = new RouterDriver();
            $this->_route_driver = App::make(RouterDriver::class);
        }
        return $this->_route_driver;
    }

    /**
     * 检测顶层分组的配置选项
     */
    protected function checkParentOptions()
    {
        if (empty($this->_middleware) && !empty(RouteFacade::$middleware)) {
            $this->_middleware = is_array(RouteFacade::$middleware) ? RouteFacade::$middleware : [RouteFacade::$middleware];
        }
        if (empty($this->_namespace) && RouteFacade::$namespace) {
            $this->_namespace = trim(RouteFacade::$namespace, '\\');
        }
        if (empty($this->_prefix) && RouteFacade::$route_prefix) {
            $this->_prefix = trim(RouteFacade::$route_prefix, '/');
        }
    }

    public function initParentOptions()
    {
        $this->_middleware = null;
        $this->_namespace = '';
        $this->_prefix = '';
        return $this;
    }


    /**
     * 子分组
     * @param array $options
     * @param \Closure $closure
     */
    public function group(array $options, \Closure $closure)
    {
        $this->checkParentOptions();
        // 当前分组的中间件、子前缀和子命名空间
        $middleware = isset($options['middleware']) ? $options['middleware'] : null;
        $prefix = isset($options['prefix']) ? $options['prefix'] : '';
        $namespace = isset($options['namespace']) ? $options['namespace'] : '';
        // 上级分组的中间件、前缀和命名空间
        $originMiddleware = $this->_middleware;
        $originPrefix = $this->_prefix;
        $originNamespace = $this->_namespace;
        if ($middleware) {
            // 合并中间件
            is_array($middleware) ? '' : $middleware = [$middleware];
            if (empty($this->_middleware)) {
                $this->_middleware = $middleware;
            } else {
                $this->_middleware = array_merge($originMiddleware, $middleware);
            }
        }

        if ($prefix) {
            // 合并前缀
            $this->_prefix .= '/' . trim($prefix, '/');
        }
        if ($namespace) {
            // 合并命名空间
            $this->_namespace .= '\\' . trim($namespace, '\\');
        }
        call_user_func($closure, $this);
        // 还原上级配置
        $this->_middleware = $originMiddleware;
        $this->_prefix = $originPrefix;
        $this->_namespace = $originNamespace;
    }


    /**
     * @param $name
     * @param $arguments
     * @return RouterDriver
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this->route($name, $arguments);
    }

}