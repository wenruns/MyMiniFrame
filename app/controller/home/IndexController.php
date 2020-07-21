<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/13
 * Time: 14:44
 */

namespace app\controller\home;


use app\controller\BaseController;
use app\models\AdminUserPermissions;
use app\models\Users;

class IndexController extends BaseController
{
    public function index()
    {
        $model = new Users();
        $model->join(AdminUserPermissions::class, 'user_id', 'id', '=', 'aup')->where('id', 1)->orderBy('id', '')->alias('u')->get();


        return view('admin.index.index');
    }
}