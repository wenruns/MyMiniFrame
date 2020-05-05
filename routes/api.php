<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30
 * Time: 17:15
 */
Router::get('a/b/{id}/{uid}', Controller::class);

Router::get('a/b/{id}/edit', 'Controller@index');