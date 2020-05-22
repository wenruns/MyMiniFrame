<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/21
 * Time: 11:24
 */

namespace core\router;

use core\facades\Route as RouteFacade;
use core\wen\App;

class Route
{
    protected $_route_driver = null;

    public function getRouteDriver()
    {
        if (empty($this->_route_driver)) {
            $this->_route_driver = App::make(RouterDriver::class);
        }
        return $this->_route_driver;
    }

    public function name($name)
    {
        $this->getRouteDriver()->name($name);
        return $this;
    }


    public function post($uri, $closure)
    {
        return RouteFacade::post($uri, $closure);
    }

    public function get($uri, $closure)
    {
        return RouteFacade::get($uri, $closure);
    }

    public function delete($uri, $closure)
    {
        return RouteFacade::delete($uri, $closure);
    }

    public function put($uri, $closure)
    {
        return RouteFacade::put($uri, $closure);
    }

    public function head($uri, $closure)
    {
        return RouteFacade::head($uri, $closure);
    }

    public function options($uri, $closure)
    {
        return RouteFacade::options($uri, $closure);
    }

    public function connect($uri, $closure)
    {
        return RouteFacade::connect($uri, $closure);
    }


    public function trace($uri, $closure)
    {
        return RouteFacade::trace($uri, $closure);
    }

    public function any($uri, $closure)
    {
        return RouteFacade::any($uri, $closure);
    }
}