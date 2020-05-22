<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 14:04
 */

error_reporting(0);


require_once __DIR__ . '/const.php';
require_once __DIR__ . '/func.php';

file_exists(ROOT_PATH . DS . 'vendor' . DS . 'autoload.php') && require_once(ROOT_PATH . DS . 'vendor' . DS . 'autoload.php');


function autoload($class)
{
    $file = ROOT_PATH . DS . $class . '.php';
    if (file_exists($file)) {
        require_once($file);
    } else {
        throw new \Exception("Class  \"$class\" not found.");
    }
}

spl_autoload_register('autoload');

register_shutdown_function(array(\core\wen\exception\ParseException::class, 'throwException'));


set_error_handler(array(\core\wen\exception\ParseException::class, 'errorHandle'), E_ALL | E_STRICT);  // 注册错误处理方法来处理所有错误


//$autoload = new \core\autoload\Autoload();
//
//spl_autoload_unregister('autoload');
//
//$autoload->registerLoader();

