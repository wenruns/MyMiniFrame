<?php
/**
 * 钩子
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 15:03
 */

namespace core\wen;


abstract class Hooks
{
    protected $app = null;

    protected $response = null;

    public function __construct(App $app, Response $response)
    {
        $this->app = $app;
        $this->response = $response;
    }

    abstract public function register();


    public function run()
    {
        $params = func_get_args();
        $hooks = $this->register();
        if (is_array($hooks)) {
            foreach ($hooks as $hook) {
                $this->app->make($hook)->handle(...$params);
            }
        } else {
            $this->app->make($hooks)->handle(...$params);
        }
    }
}