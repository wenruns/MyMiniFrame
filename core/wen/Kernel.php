<?php
/**
 * 内核
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
    /**
     * 应用缓存
     * @var App|null
     */
    protected $app = null;

    /**
     * 响应对象
     * @var Response|null
     */
    protected $response = null;

    /**
     * 路由驱动
     * @var null
     */
    protected $routeDriver = null;

    /**
     * 配置实例
     * @var Config|null
     */
    protected $config = null;

    /**
     * 请求对象
     * @var Request|null
     */
    protected $request = null;

    /**
     * 动作
     * @var null
     */
    private $__action = null;

    /**
     * Kernel constructor.
     * @param App $app
     */
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

    protected function accessControlAllowOrigin()
    {
        $accesses = $this->config->get('app.access_control');
        if (empty($accesses)) {
            return $this;
        }
        if (!empty($accesses['allow_origins'])) {
            foreach ($accesses['allow_origins'] as $origin) {
                header("Access-Control-Allow-Origin: $origin");
            }
        }
        if (!empty($accesses['allow_methods'])) {
            header('Access-Control-Allow-Methods: ' . implode(',', $accesses['allow_methods']));
        }
        if (!empty($accesses['allow_headers'])) {
            header('Access-Control-Allow-Headers: ' . implode(',', $accesses['allow_headers']));
        }
        return $this;
    }

    /**
     * 业务逻辑处理入口
     * @return Response|null
     */
    public function handle()
    {
        try {
            $this->accessControlAllowOrigin()
                ->runBeforeHooks()
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

    /**
     * 启动app
     * @return $this
     * @throws RouteException
     * @throws \ReflectionException
     */
    public function runApp()
    {
        $this->loadRoutes()->doAction()->runAfterMethodHook($this->__action);
        return $this;
    }

    /**
     * 执行应用前置钩子
     * @return $this
     * @throws \Exception
     */
    public function runBeforeHooks()
    {
        $this->app->make(BeforeApp::class, [$this->app, $this->response])->run();
        return $this;
    }

    /**
     * 执行应用后置钩子
     * @return $this
     * @throws \Exception
     */
    public function runAfterHooks()
    {
        $this->app->make(AfterApp::class, [$this->app, $this->response])->run();
        return $this;
    }

    /**
     * 执行方法前置钩子
     * @param $action
     * @return $this
     * @throws \Exception
     */
    protected function runBeforeMethodHook($action)
    {
        $this->__action = $action;
        $this->app->make(BeforeMethod::class, [$this->app, $this->response])->run($action);
        return $this;
    }

    /**
     * 执行方法后置钩子
     * @param $action
     * @return $this
     * @throws \Exception
     */
    protected function runAfterMethodHook($action)
    {
        $this->app->make(AfterMethod::class, [$this->app, $this->response])->run($action);
        return $this;
    }


    /**
     * 获取路由驱动
     * @return mixed|null
     * @throws \Exception
     */
    public function getRouteDriver()
    {
        if (empty($this->routeDriver)) {
            $this->routeDriver = $this->app->make(RouterDriver::class);
        }
        return $this->routeDriver;
    }

    /**
     * 加载路由
     * @return $this
     * @throws \Exception
     */
    protected function loadRoutes()
    {
//        $this->getRouteDriver()->loadRoutes(ROUTER_PATH)->getAction();
        $this->getRouteDriver()->loadRoutes(ROUTER_PATH);
        return $this;
    }

    /**
     * 执行业务逻辑
     * @return $this
     * @throws RouteException
     * @throws \ReflectionException
     */
    protected function doAction()
    {
        // 获取动作
        $action = $this->getRouteDriver()->getAction();
        if ($action === false) {
            if ($this->config('app_debug', false)) {
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
            if ($param_class = $param->getClass()) {
                $value = App::make($param_class->getName(), $this->initParamObject($param_class->getName()));
            } else {
                try {
                    $value = isset($method_params[$param->getName()]) ? $method_params[$param->getName()] : $param->getDefaultValue();
                } catch (\Exception $e) {
                    throw new \Exception('缺少参数：' . $param->getName());
                }
            }
            $param_values[$param->getPosition()] = $value;
        }
        $this->runBeforeMethodHook($action)->response->content($instance->$method(...$param_values));
        return $this;
    }

    protected function initParamObject($class)
    {
        $params = [];
        $valueReflection = new \ReflectionClass(App::checkClassName($class));
        $constructParams = $valueReflection->getConstructor()->getParameters();
        foreach ($constructParams as $item) {
            if ($classParam = $item->getClass()) {
                $params[$item->getPosition()] = App::make($classParam->getName(), $this->initParamObject($classParam->getName()));
            } else {
                try {
                    $params[$item->getPosition()] = request($item->getName()) ? request($item->getName()) : $item->getDefaultValue();
                } catch (\Exception $e) {
                    throw new \Exception('缺少参数：' . $item->getName());
                }
            }
        }
        return $params;
    }


    /**
     * 控制器检查
     * @param $controller
     * @param $namespace
     * @return string
     */
    protected function checkController($controller, $namespace)
    {
        $namespaceConfig = $this->config('app.namespace', ['root' => '\\src\\', 'map' => []]);
        $root = $namespaceConfig['root'];
        $map = $namespaceConfig['map'];

        if (!empty($map) && !empty($namespace) && isset($map[$namespace])) {
            $root = $map[$namespace];
            $namespace = '';
        }

        return $root . ($namespace ? $namespace . '\\' : '') . $controller;
    }


    /**
     * 实例化控制器
     * @param $controller
     * @return mixed
     */
    protected function instanceResolve($controller)
    {
        if (is_object($controller)) {
            return $controller;
        }
        return $this->app->make($controller);
    }

    /**
     * 获取配置
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function config($key = '', $default = null)
    {
        return $this->config->get($key, $default);
    }

}