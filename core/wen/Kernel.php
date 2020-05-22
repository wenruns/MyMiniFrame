<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 14:47
 */

namespace core\wen;


use core\router\Route;
use core\router\RouterDriver;
use core\wen\exception\RouteException;
use core\wen\output\Output;
use hooks\AfterApp;
use hooks\AfterMethod;
use hooks\BeforeApp;
use hooks\BeforeMethod;

class Kernel
{
    protected $app = null;

    protected $response = null;

    protected $router = null;

    protected $config = null;

    protected $request = null;

    private $__action = null;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->response = new Response();
        $this->request = new Request();
        $this->config = new Config();
        App::addInstance(Response::class, $this->response);
        App::addInstance(Request::class, $this->request);
        App::addInstance(Config::class, $this->config);
    }

    public function handle()
    {
        try {
            $this->runBeforeHooks()
                ->runApp()
                ->runAfterHooks();
        } catch (\Error $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\Exception $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\ParseError $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\ErrorException $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\DivisionByZeroError $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\CompileError $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\AssertionError $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\ArithmeticError $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        } catch (\TypeError $e) {
            $this->response->statusCode(500)->desc('代码执行报错')->error($e->getMessage())->content('', $e);
        }
        return $this->response;
    }

    public function runApp()
    {
        $this->loadRoutes()->doAction()->runAfterMethodHook($this->__action);
        return $this;
    }

    public function runBeforeHooks()
    {
        $this->app->make(BeforeApp::class, [$this->app, $this->response])->run();
        return $this;
    }


    public function runAfterHooks()
    {
        $this->app->make(AfterApp::class, [$this->app, $this->response])->run();
        return $this;
    }

    protected function runBeforeMethodHook($action)
    {
        $this->__action = $action;
        $this->app->make(BeforeMethod::class, [$this->app, $this->response])->run($action);
        return $this;
    }

    protected function runAfterMethodHook($action)
    {
        $this->app->make(AfterMethod::class, [$this->app, $this->response])->run($action);
        return $this;
    }


    public function getRouter()
    {
        if (empty($this->router)) {
            $this->router = $this->app->make(RouterDriver::class);
        }
        return $this->router;
    }

    protected function loadRoutes()
    {
        $this->getRouter()->loadRoutes(ROUTER_PATH)->getAction();
        return $this;
    }

    protected function doAction()
    {
        $action = $this->getRouter()->getAction();
        if ($action === false) {
            if ($this->config('APP_DEBUG', false)) {
                throw new RouteException('No route found');
            }
            $this->response->error('Route matching failed.')->desc('No route found')->statusCode(404);
            return $this;
        }

        $method_params = $action['params'];
        $controller = $action['controller'];
        $method = $action['method'];
        $namespace = $action['namespace'];

        $controller = $this->checkController($controller, $namespace);

        $instance = $this->instanceResolve($controller);
        $reflection = new \ReflectionClass($instance);
        $params = $reflection->getMethod($method)->getParameters();
        $param_values = [];
        foreach ($params as $param) {
            try {
                if ($param_class = $param->getClass()) {
                    $value = App::make($param_class->getName());
                } else {
                    $value = isset($method_params[$param->getName()]) ? $method_params[$param->getName()] : $param->getDefaultValue();
                }
                $param_values[$param->getPosition()] = $value;
            } catch (\Exception $e) {
                throw new \Exception('缺少参数：' . $param->getName());
            }
        }

        $this->runBeforeMethodHook($action)->response->content($instance->$method(...$param_values));
        return $this;
    }


    protected function checkController($controller, $namespace)
    {
        $namespaceConfig = $this->config('app.namespace', ['root' => '\\src\\', 'map' => []]);
        $root = $namespaceConfig['root'];
        $map = $namespaceConfig['map'];
        if (empty($map) || strpos($controller, '\\') === false) {
            return $root . ($namespace ? $namespace . '\\' : '') . $controller;
        }
        $len = strpos($controller, '\\');
        $prefix = substr($controller, 0, $len);
        if (isset($map[$prefix])) {
            $controller = substr($controller, $len + 1);
            $root = $map[$prefix];
        }
        return $root . ($namespace ? $namespace . '\\' : '') . $controller;
    }


    protected function instanceResolve($controller)
    {
        if (is_object($controller)) {
            return $controller;
        }
        return $this->app->make($controller);
    }

    public function config($key = '', $default = null)
    {
        return $this->config->get($key, $default);
    }

}