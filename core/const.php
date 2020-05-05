<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 17:14
 */
defined('ROOT_PATH') || define('ROOT_PATH', substr(__DIR__, 0, strrpos(__DIR__, DS)));

defined('ROUTER_PATH') || define('ROUTER_PATH', ROOT_PATH . DS . 'routes');

defined('CORE_PATH') || define('CORE_PATH', __DIR__);

defined('SRC_PATH') || define('SRC_PATH', ROOT_PATH . DS . 'src');

defined('ERROR_PATH') || define('ERROR_PATH', CORE_PATH . DS . 'wen' . DS . 'error');
