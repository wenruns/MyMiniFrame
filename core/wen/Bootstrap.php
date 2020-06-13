<?php
/**
 * 接口映射
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/11
 * Time: 11:06
 */

namespace core\wen;

class Bootstrap
{
    protected $_map = [];

    /**
     * @param $interfaceClass
     * @param $instanceClass
     * @return $this
     */
    public function register($interfaceClass, $instanceClass)
    {
        $this->_map[$interfaceClass] = $instanceClass;
        return $this;
    }

    public function getMap()
    {
        return $this->_map;
    }


    public function handle()
    {
        $bootstrap = new \app\Bootstrap();
        $bootstrap->handle();
        $this->_map = $bootstrap->getMap();
        return $this;
    }

    public function getInstanceClass($className)
    {
        return isset($this->_map[$className]) ? $this->_map[$className] : $className;
    }
}