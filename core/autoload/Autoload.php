<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 9:37
 */

namespace core\autoload;

class Autoload
{


    public function registerLoader()
    {
        spl_autoload_register([$this, 'autoLoad']);
    }

    protected function autoLoad($class)
    {

        require_once(str_replace('\\', DS, ROOT_PATH . DS . $class) . '.php');
    }


}