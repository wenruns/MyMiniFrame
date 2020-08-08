<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/6
 * Time: 16:21
 */

namespace core\wen;


class Collect
{
    protected $_options = [];

    public function __construct($options = [])
    {
        $this->_options = $options;
    }

    public function each(\Closure $closure)
    {
        $res = null;
        foreach ($this->_options as $key => $item) {
            $res = $res || call_user_func($closure, $item, $key);
        }
        return $res;
    }

}