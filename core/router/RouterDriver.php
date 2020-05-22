<?php
/**
 * Created by PhpStorm.
 * User: wem
 * Date: 2019/10/30
 * Time: 17:18
 *
 *
 * 生成一个缓存文件（命名空间缓存、映射缓存、）
 */

namespace core\router;


use core\wen\App;
use core\wen\request\Get;
use core\wen\request\Server;
use middlewares\Middleware;
use core\facades\Route;

class RouterDriver
{
    /**
     * 路由缓存池
     * @var array
     */
    protected $pools = [];

    protected $current_uri = '';

    /**
     * 别名map
     * @var array
     */
    protected $aliasPools = [];

    /**
     * 匹配的路由
     * @var string
     */
    protected $match_uri = '';

    /**
     * 路由解析
     * @param $uri
     * @param $closure
     * @param $method
     * @param $prefix
     * @param $middleware
     * @param $namespace
     * @param $route_prefix
     * @return $this
     * @throws \Exception
     */
    public function routeParsing($uri, $closure, $method, $prefix, $middleware, $namespace, $route_prefix)
    {
        // todo:: 路由缓存池
        $prefix = trim($prefix, '/');
        $uri = trim($uri, '/');
        $route_prefix = trim($route_prefix, '/');
        if ($prefix != config('router.default', 'web')) {
            $uri = $prefix . '/' . (empty($route_prefix) ? '' : $route_prefix . '/') . $uri;
        } else if ($route_prefix) {
            $uri = $route_prefix . '/' . $uri;
        }
        if (empty($uri)) {
            $uri = '/';
        }
        $namespace = trim($namespace, '\\');
//        if ($namespace && !($closure instanceof \Closure)) {
//            $closure = $namespace . '\\' . ltrim($closure, '\\');
//        }

        $this->current_uri = $uri;
        $this->pools[base64_encode($uri)] = [
            'method' => $method,
            'closure' => $closure,
            'prefix' => $prefix,
            'namespace' => $namespace,
            'middleware' => $middleware,
        ];
        $this->matchRouter($uri);
        return $this;
    }


    /**
     * 匹配路由
     * @param $uri
     * @return $this
     */
    protected function matchRouter($uri)
    {
        // 请求路由
        $server = App::make(Server::class);
        $request = $server->get('REQUEST_URI');
        $script_uri = $server->get('SCRIPT_NAME');
        $self_uri = $server->get('PHP_SELF');

        preg_match_all('/\{([^\/]+)\}/', $uri, $result_uri);
        // todo:: 路由匹配规则
        $reg = '/^' . str_replace('/', '\/', $uri) . '(\/+.*|\/*|)/';
        $test_uri = $uri;
        foreach ($result_uri[0] as $key => $vo) {
            $reg = str_replace($vo, '([^\/]+)', $reg);
            $test_uri = str_replace($vo, '', $test_uri);
        }
        $request_uri = ltrim($request, ($script_uri ? $script_uri : $self_uri)); // 请求uri
        $request_uri = rtrim($request_uri, '/') . '/';
        // todo:: 匹配路由
        if (preg_match_all($reg, $request_uri, $params)) {
            if (empty($this->match_uri) || $request_uri == $uri || $this->checkBestRoute($params, $request_uri, $test_uri)) {
                // 保存匹配成功的路由
                $this->match_uri = [
                    'uri' => $uri,
                    'preg_result' => $result_uri,
                    'params' => $params
                ];
            }
        }
        return $this;
    }

    /**
     *
     * @param $params
     * @param $request_uri
     * @param $test_uri
     * @return bool
     */
    protected function checkBestRoute($params, $request_uri, $test_uri)
    {
        array_shift($params);
        array_pop($params);
        foreach ($params as $key => $vo) {
            foreach ($vo as $item) {
                $request_uri = str_replace($item, '', $request_uri);
            }
        }
        $request_uri = trim($request_uri, '/');
        if (strpos($request_uri, $test_uri) >= 0) {
            return true;
        }
        return false;
    }

    /**
     * 路由别名
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->aliasPools[$name] = $this->current_uri;
        return $this;
    }

    public function getRoutes()
    {
        return $this->pools;
    }

    public function getAlias()
    {
        return $this->aliasPools;
    }

    public function getAction()
    {
        if (empty($this->match_uri)) {
            return false;
        }
        $params = $this->paramsProcessor();

        $uri = $this->match_uri['uri'];
        $info = $this->pools[base64_encode($uri)];
        $this->runMiddleware($info['middleware']);
        $closure = explode('@', $info['closure']);
        return [
            'controller' => $closure[0],
            'method' => $closure[1],
            'params' => $params,
            'namespace' => $info['namespace']
        ];
    }

    protected function runMiddleware($middleware)
    {
        if (empty($middleware)) {
            return;
        }

        App::make(Middleware::class)->run($middleware);
    }

    protected function paramsProcessor()
    {
        $method_params = [];

        $param_values = $this->match_uri['params'];
        $param_names = $this->match_uri['preg_result'];
        array_shift($param_names);
        array_shift($param_values);

        foreach ($param_names as $k => $item) {
            foreach ($item as $key => $name) {
                $method_params[$name] = isset($param_values[$k][$key]) ? $param_values[$k][$key] : '';
                App::make(Get::class)->addAttribute($name, $method_params[$name]);
            }
            if (isset($param_values[$k])) {
                unset($param_values[$k]);
            }
        }

        foreach ($param_values as $k => $item) {
            foreach ($item as $key => $vo) {
                $vo = explode('/', trim($vo, '/'));
                $n = count($vo);
                for ($ky = 0; $ky < $n; $ky++) {
                    if (!isset($vo[$ky + 1])) {
                        App::make(Get::class)->addAttribute($vo[$ky], '');
                        break;
                    }
                    App::make(Get::class)->addAttribute($vo[$ky], $vo[++$ky]);
                }
            }
        }
        return $method_params;
    }

    public function loadRoutes($dir_path, $prefix = '')
    {
        foreach (scandir($dir_path) as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir_path . DS . $file)) {
                    $this->loadRoutes($dir_path . DS . $file, $prefix . '/' . $file);
                } else if (is_file($dir_path . DS . $file)) {
                    Route::$prefix = $prefix . '/' . substr($file, 0, strpos($file, '.'));
                    include_once $dir_path . DS . $file;
                }
            }
        }
        return $this;
    }

}
