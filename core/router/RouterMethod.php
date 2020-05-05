<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/9
 * Time: 17:48
 */
class RouterMethod{

    public function post($uri, $closure)
    {
        return Router::post($uri, $closure);
    }

    public function get($uri, $closure)
    {
        return Router::get($uri, $closure);
    }

    public function delete($uri, $closure)
    {
        return Router::delete($uri, $closure);
    }

    public function put($uri, $closure)
    {
        return Router::put($uri, $closure);
    }

    public function head($uri, $closure)
    {
        return Router::head($uri, $closure);
    }

    public function options($uri, $closure)
    {
        return Router::options($uri, $closure);
    }

    public function connect($uri, $closure)
    {
        return Router::connect($uri, $closure);
    }


    public function trace($uri, $closure)
    {
        return Router::trace($uri, $closure);
    }

    public function any($uri, $closure)
    {
        return Router::any($uri, $closure);
    }
}

