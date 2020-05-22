<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 14:21
 */

namespace core\wen\request;


abstract class AbstractRequest
{
    private $__attributes = [];

    private $__arr = [];


    abstract public function data();

    abstract public function handle();


    public function __construct()
    {
        foreach ($this->data() as $key => $value) {
            $attrName = '_' . $key;
            $this->$attrName = $value;
            $this->__attributes[] = $key;
        }
        $this->handle();
    }

    public function remove($index)
    {
        $attrName = '_' . $index;
        unset($this->$attrName);
        if (isset($this->__arr[$index])) {
            unset($this->__arr[$index]);
        }
    }

    public function clear()
    {
        foreach ($this->__attributes as $key) {
            $attrName = '_' . $key;
            unset($this->$attrName);
            if (isset($this->__arr[$key])) {
                unset($this->__arr[$key]);
            }
        }
    }


    public function get($index = '', $default = null)
    {
        if (empty($index)) {
            return $this->toArray();
        }

        $index = explode('.', $index);
        $key = '_' . array_shift($index);
        if (!isset($this->$key)) {
            return $default;
        }

        if (empty($index)) {
            return $this->$key;
        }

        $data = $this->$key;
        foreach ($index as $dex) {
            if (!isset($data[$dex])) {
                return $default;
            }
            $data = $data[$dex];
        }
        return $data;
    }

    public function toArray()
    {
        if (empty($this->__arr)) {
            foreach ($this->__attributes as $key) {
                $attrName = '_' . $key;
                $this->__arr[$key] = $this->$attrName;
            }
        }
        return $this->__arr;
    }


    public function addAttribute($key, $value)
    {
        $attrName = '_' . $key;
        $this->$attrName = $value;
        $this->__attributes[] = $key;
        $this->__arr[$key] = $value;
    }
}