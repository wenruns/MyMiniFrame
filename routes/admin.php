<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/21
 * Time: 11:18
 */

use \core\facades\Route;
use  \core\router\Route as Router;

//Route::group([
//    'namespace' => 'admin',
//    'prefix' => '',
//    'middleware' => []
//], function (Router $router) {
//    $router->any('/', 'IndexController@index');
//    $router->group([
//        'namespace' => 'test',
//        'prefix' => '',
//        'middleware' => []
//    ], function (Router $router) {
//        $router->any('test', 'IndexController@index');
//    });
//});