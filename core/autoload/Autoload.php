<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 9:37
 */

class Autoload
{
    public function __construct()
    {
        $this->loading();
        $this->register();
    }

    public function register()
    {
        spl_autoload_register([$this, 'autoLoad']);
        require_once(ROOT_PATH . DS . 'vendor' . DS . 'autoload.php');
    }

    protected function autoLoad($class)
    {
        require_once(str_replace('\\', DS, ROOT_PATH . DS . $class) . '.php');
    }

    protected function loading()
    {
        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
        require_once __DIR__.DS.'..'.DS.'const.php';
        require_once CORE_PATH . DS . 'func.php';
        require_once CORE_PATH . DS . 'wen' . DS . 'output' . DS . 'Output.php';
        require_once CORE_PATH .DS.'router'.DS.'RouterDriver.php';
    }
}

return new Autoload();