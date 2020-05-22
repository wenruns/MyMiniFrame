<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/21
 * Time: 11:20
 */

namespace app\controller\admin;

use app\controller\Controller;

class IndexController
{
    public function index(Controller $controller = null)
    {
        return view('', [
            'a' => ['<a>test</a>', 1231123, 1231]
        ]);
    }
}