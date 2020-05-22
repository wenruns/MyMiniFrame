<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/21
 * Time: 11:18
 */

use \core\facades\Route;

Route::group([
    'namespace' => 'admin'
], function (\core\router\Route $router) {
    $router->any('/', 'IndexController@index');

});