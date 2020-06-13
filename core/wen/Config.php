<?php
/**
 * 配置管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 15:06
 */

namespace core\wen;


class Config
{
    private $__configs = [];

    private $__path = '';

    public function __construct()
    {
        $this->__path = ROOT_PATH . DS . 'configs';
    }

    public function get($index = '', $default = null)
    {
        if (empty($this->__configs)) {
            $this->loadConfigs();
        }
        if (empty($index)) {
            return $this->__configs;
        }
        $index = explode('.', $index);
        $first = array_shift($index);

        if (isset($this->__configs[$first])) {
            $configs = $this->__configs[$first];
        } else if (isset($this->__configs['app'][$first])) {
            $configs = $this->__configs['app'][$first];
        } else {
            return $default;
        }

        foreach ($index as $dex) {
            if (!isset($configs[$dex])) {
                return $default;
            }
            $configs = $configs[$dex];
        }
        return $configs;
    }

    protected function loadConfigs()
    {
        $files = scandir($this->__path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $dex = substr($file, 0, strrpos($file, '.'));
                $this->__configs[$dex] = require($this->__path . DS . $file);
            }
        }
    }
}