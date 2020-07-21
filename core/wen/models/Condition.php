<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 17:32
 */

namespace core\wen\models;


class Condition
{
    protected $method = '';

    protected $arguments = [];


    public function __construct($method, $arguments)
    {
        $this->method = $method;
        $this->arguments = $arguments;
    }
}