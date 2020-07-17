<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 17:17
 */

use \core\wen\output\Output;
use \core\wen\App;

if (!function_exists('dd')) {
    /**
     * 打印输出
     * @throws Exception
     */
    function dd()
    {
        $args = func_get_args();
        if (empty($args)) {
            throw new \Exception('function dd need one argument at least.');
        }

        Output::show($args);
        exit(0);
    }
}

if (!function_exists('dump')) {
    /**
     * @throws Exception
     */
    function dump()
    {
        $args = func_get_args();
        if (empty($args)) {
            throw new \Exception('function dump need one argument at least.');
        }
        Output::show($args);
    }
}

if (!function_exists('env')) {
    /**
     * 获取环境变量
     * @param string $index
     * @param string $default
     * @return array|bool|mixed|string
     */
    function env($index = '', $default = '')
    {
        static $env = [];
        if (empty($env)) {
            $str = file(ROOT_PATH . DS . '.env');
            foreach ($str as $key => $item) {
                if (strpos($item, '#') !== 0) {
                    $a = explode('=', $item);
                    $env[trim($a[0])] = trim($a[1]);
                }
            }
        }
        if (empty($index)) {
            return $env;
        }
        if (!isset($env[$index])) {
            return $default;
        }
        $value = $env[$index];
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return '';
        }
        return $value;
    }
}


if (!function_exists('storage_path')) {
    /**
     * 返回存储路径
     * @param string $path
     * @return string
     */
    function storage_path($path = '')
    {
        return ROOT_PATH . DS . 'storage' . DS . str_replace('/', DS, $path);
    }
}

if (!function_exists('storage')) {
    /**
     * @param $fileName
     * @param $content
     * @param string $path
     * @param int $flags
     * @param null $context
     * @return bool|int
     */
    function storage($fileName, $content, $path = '', $flags = 0, $context = null)
    {
        if (!is_dir(storage_path($path))) {
            mkdir(storage_path($path), 0777, true);
            chmod(storage_path($path), 0777);
        }
        return file_put_contents(storage_path($path) . DS . $fileName, $content, $flags, $context);
    }
}

if (!function_exists('public_path')) {
    /**
     * @param string $path
     * @return string
     */
    function public_path($path = '')
    {
        return ROOT_PATH . DS . 'public' . $path;
    }
}


if (!function_exists('config')) {
    /**
     * 获取配置信息
     * @param string $index
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    function config($index = '', $default = null)
    {
        return App::make(\core\wen\Config::class)->get($index, $default);
    }
}

if (!function_exists('request')) {
    /**
     * 获取请求参数
     * @param string $index
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    function request($index = '', $default = null)
    {
        return App::make(core\wen\Request::class)->param($index, $default);
    }
}

if (!function_exists('request_post')) {
    /**
     * @param string $index
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    function request_post($index = '', $default = null)
    {
        return App::make(\core\wen\Request::class)->post($index, $default);
    }
}

if (!function_exists('request_get')) {
    /**
     * @param string $index
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    function request_get($index = '', $default = null)
    {
        return App::make(\core\wen\Request::class)->get($index, $default);
    }
}
if (!function_exists('server')) {
    /**
     * @param string $index
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    function server($index = '', $default = null)
    {
        return App::make(\core\wen\Request::class)->server($index, $default);
    }
}

if (!function_exists('post')) {
    /**
     * @param string $index
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    function post($index = '', $default = null)
    {
        return App::make(\core\wen\Request::class)->post($index, $default);
    }
}

if (!function_exists('isTrue')) {
    // 判断是否为真
    function isTrue($data, $index)
    {
        if (!isset($data[$index]) || empty($data[$index])) {
            return false;
        }
        return true;
    }
}


if (!function_exists('apiResponse')) {
    // api响应
    function apiResponse($msg)
    {
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }
        echo $msg;
        exit(0);
    }
}

if (!function_exists('get_client_IP')) {
    /**
     * 获取客户端IP地址
     * @return array|false|string
     * @throws Exception
     */
    function get_client_IP()
    {
        $request = App::make(\core\wen\Request::class);
        $server = $request->server();
        if (empty($server)) {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realIp = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $realIp = getenv('HTTP_CLIENT_IP');
            } else {
                $realIp = getenv('REMOTE_ADDR');
            }
        } else {
            if (isset($server['HTTP_X_FORWARDED_FOR'])) {
                $realIp = $server['HTTP_X_FORWARDED_FOR'];
            } else if (isset($server['HTTP_CLIENT_IP'])) {
                $realIp = $server['HTTP_CLIENT_IP'];
            } else {
                $realIp = $server['REMOTE_ADDR'];
            }
        }
        return $realIp;
    }
}


if (!function_exists('view')) {
    /**
     * @param string $template
     * @param array $variables
     * @return \core\wen\View|mixed
     * @throws Exception
     */
    function view($template = '', $variables = [])
    {
        return App::make(\core\wen\View::class)->template($template)->variables($variables);
    }
}


if (!function_exists('view_layout')) {
    /**
     * @param string $template
     * @param array $options
     */
    function view_layout($template = '', $options = [])
    {
        extract($options);
        include($template);
    }
}





