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

class RouterDriver
{
    protected $pools = [];

    protected $temporaryPools = [];

    protected $aliasPools = [];

    protected $match_uri = '';

    public function routeParsing($uri, $closure, $method, $prefix)
    {
        // todo:: 路由缓存池
        if (empty($uri)) {
            $uri = '/';
        }
        if (Router::$prefix != 'web') {
            $uri = trim(Router::$prefix . '/' . $uri, '/');
        }
        $this->pools[base64_encode($uri)] = [
            'method' => $method,
            'closure' => $closure,
            'prefix' => $prefix,
        ];
//        if (empty($this->match_uri)) {
        // 匹配参数
        $request = $_SERVER['REQUEST_URI'];
        $script_uri = $_SERVER['SCRIPT_NAME'];
        $self_uri = $_SERVER['PHP_SELF'];
        preg_match_all('/\{([^\/]+)\}/', $uri, $result_uri);
        // todo:: 路由匹配规则
        $reg = '/^' . str_replace('/', '\/', $uri) . '$/';
        foreach ($result_uri[0] as $key => $vo) {
            $reg = str_replace($vo, '([^\/]+)', $reg);
        }
        $request_uri = ltrim($request, ($script_uri ? $script_uri : $self_uri)); // 请求uri
        // todo:: 匹配路由
        preg_match_all($reg, $request_uri, $result_request_uri);
        if (count($result_request_uri[0]) == 1 && (empty($this->match_uri) || count($result_request_uri) < count($this->match_uri['result_request_uri']))) {
            // 保存匹配成功的路由
            $this->match_uri = [
                'uri' => $uri,
                'result_uri' => $result_uri,
                'result_request_uri' => $result_request_uri
            ];
        }
//        }
    }

    public function name($name)
    {
        $this->aliasPools[$name] = $this->uri;
    }

    public function run()
    {
        if (empty($this->match_uri)) {
            dd('未找到路由');
        }
        $uri = $this->match_uri['uri'];
        $info = $this->pools[base64_encode($uri)];
        $closure = $info['closure'];
        if (is_callable($closure)) {
            $return_var = call_user_func($info['closure']);
        } else {
            require_once SRC_PATH . DS . $closure . '.php';
            if (strpos($closure, '@') === false) {
                $className = '\src\\' . $closure;
                preg_match('/(\/edit\/|\/edit$|\/create\/|\/create$|\/delete\/|\/delete$)/', $uri, $output);
                if (empty($output) || !isset($output[1])) {
                    $output[1] = 'index';
                }
                switch (trim($output['1'], '/')) {
                    case 'edit':
                        $method = 'edit';
                        break;
                    case 'create':
                        $method = 'create';
                        break;
                    case 'delete':
                        $method = 'delete';
                        break;
                    default:
                        $method = 'index';
                }
            } else {
                $classes = explode('@', $closure);
                $className = '\src\\'.$classes[0];
                $method = $classes[1];
            }
            if (class_exists($className)) {
                $instance = new $className();
                $return_var = $instance->$method();
            } else {
                throw new \Exception('是是是');
            }
        }
        $this->makeResponse($return_var);
    }

    protected function makeResponse($return_var)
    {
//        dd($this->pools, base64_encode($this->match_uri['uri']));
        if ($this->pools[base64_encode($this->match_uri['uri'])]['prefix'] == '/api') {
            apiResponse($return_var);
        } else {
            echo $return_var;
        }
    }


}

include_once __DIR__ . DS . 'Router.php';