<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/31
 * Time: 11:08
 */


require_once __DIR__ . '/../core/Autoload.php';

$app = new \core\wen\App();

$kernel = $app->make(\core\wen\Kernel::class, [$app]);

$response = $kernel->handle();

$response->output();




////dd($_SERVER['REQUEST_URI']);
////dd(request());
//exec('ls', $output, $return_var);
//dd($output, $return_var);