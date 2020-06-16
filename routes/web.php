<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/5
 * Time: 11:38
 */

use \core\facades\Route;
use \core\router\Route as Router;

Route::group([
    'prefix' => 'html2img',
    'namespace' => 'html2img'
], function (Router $router) {
    $router->any('/', 'IndexController@index');
});

Route::group([
    'prefix' => 'xml',
    'namespace' => 'xml'
], function (Router $route) {
    $route->any('/', 'ExplainFileController@accept');
});

Route::group([
    'namespace' => 'home',
], function (Router $route) {
    $route->any('/', 'IndexController@index');

});



