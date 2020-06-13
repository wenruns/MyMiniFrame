<?php
/**
 * 请求对象
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 11:42
 */

namespace core\wen;


use core\wen\request\Get;
use core\wen\request\Post;
use core\wen\request\Server;
use core\wen\traits\FileTrait;

class Request
{
    use FileTrait;

    private $__post = null;
    private $__get = null;
    private $__server = null;


    public function __construct()
    {
        $this->__get = App::make(Get::class);
        $this->__post = App::make(Post::class);
        $this->__server = App::make(Server::class);
    }

    public function get($index = '', $default = null)
    {
        return $this->__get->get($index, $default);
    }


    public function post($index = '', $default = null)
    {
        return $this->__post->get($index, $default);
    }

    public function param($index = '', $default = null)
    {
        $params = $this->toArray();
        if (empty($index)) {
            return $params;
        }
        $index = explode('.', $index);

        foreach ($index as $dex) {
            if (!isset($params[$dex])) {
                return $default;
            }
            $params = $params[$dex];
        }
        return $params;
    }


    public function server($index = '', $default = null)
    {
        return $this->__server->get($index, $default);
    }


    public function toArray()
    {
        $post = $this->__post->toArray();
        $get = $this->__get->toArray();
        return $get + $post;
    }

    public function request_method()
    {
        return $this->server('REQUEST_METHOD');
    }


    public function isPost()
    {
        if ($this->server('REQUEST_METHOD') == 'POST') {
            return true;
        }
        return false;
    }

}