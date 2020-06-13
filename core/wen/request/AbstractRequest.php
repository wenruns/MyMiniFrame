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
    protected $_attributes = [];

    protected $_arr = [];


    abstract public function data();

    abstract public function handle();


    public function __construct()
    {
        foreach ($this->data() as $key => $value) {
            if (empty($key)) {
                continue;
            }
            $attrName = '_' . $key;
            $this->$attrName = $value;
            $this->_attributes[] = $key;
        }
        $this->handle();
    }

    public function remove($index)
    {
        $attrName = '_' . $index;
        unset($this->$attrName);
        if (isset($this->_arr[$index])) {
            unset($this->_arr[$index]);
        }
    }

    public function clear()
    {
        foreach ($this->_attributes as $key) {
            $attrName = '_' . $key;
            unset($this->$attrName);
            if (isset($this->_arr[$key])) {
                unset($this->_arr[$key]);
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
        if (empty($this->_arr)) {
            foreach ($this->_attributes as $key) {
                $attrName = '_' . $key;
                $this->_arr[$key] = $this->$attrName;
            }
        }
        return $this->_arr;
    }


    public function addAttribute($key, $value)
    {
        if (empty($key)) {
            return false;
        }
        $attrName = '_' . $key;
        $this->$attrName = $value;
        $this->_attributes[] = $key;
        if (!empty($this->_arr)) {
            $this->_arr[$key] = $value;
        }
    }
}