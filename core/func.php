<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 17:17
 */

if (!function_exists('dd')) {
    // 打印输出
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
    function env($index, $default = '')
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
    // 返回存储路径
    function storage_path($path = '')
    {
        return ROOT_PATH . DS . 'storage' . DS . $path;
//        return rtrim(ROOT_PATH . DS . 'storage' . DS . $path, DS);
    }
}

if (!function_exists('public_path')) {
    function public_path($path = '')
    {
        return ROOT_PATH . DS . 'public' . $path;
    }
}


if (!function_exists('config')) {
    // 获取配置信息
    function config($index = '', $default = null)
    {
        $index = explode('.', $index);
        $config_path = ROOT_PATH . DS . 'configs';
        $config_file = $index[0] . '.php';
        if (is_file($config_path . DS . $config_file)) {
            unset($index[0]);
            $configs = require($config_path . DS . $config_file);
            foreach ($index as $key) {
                if (!isset($configs[$key])) {
                    return $default;
                }
                $configs = $configs[$key];
            }
            return $configs;
        }
        return $default;
    }
}

if (!function_exists('request')) {
    // 获取请求参数
    function request($index = '', $default = null)
    {
        $params = array_merge($_POST, $_GET);
        $index = explode('.', $index);
        foreach ($index as $key) {
            if (!isset($params[$key])) {
                return $default;
            }
            $params = $params[$key];
        }
        return $params;
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
    // 获取客户端IP地址
    function get_client_IP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realIp = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realIp = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $realIp = getenv('HTTP_CLIENT_IP');
            } else {
                $realIp = getenv('REMOTE_ADDR');
            }
        }
        return $realIp;
    }
}



