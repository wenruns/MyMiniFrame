<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/5
 * Time: 11:38
 */

use \core\facades\Route;


Route::group([
    'middleware' => '',
    'namespace' => '',
    'prefix' => '',
], function (\core\router\Route $route) {
    $route->any('test/{id}', 'Controller@show')->name('show');
    $route->any('test/test1', 'admin\\IndexController@index')->name('index');
});




