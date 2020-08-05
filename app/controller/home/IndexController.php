<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/13
 * Time: 14:44
 */

namespace app\controller\home;


use app\controller\BaseController;
use app\models\Users;
use core\wen\models\Query;

class IndexController extends BaseController
{
    public function index()
    {
        $model = new Users();
        $model->join('admin_user_permissions as aup', 'aup.user_id', 'u.id')
            ->alias('u')
            ->where(function (Query $query) {
                $query->where('id', 1);
                $query->orWhere('id', 2);
            })
            ->orWhere(function (Query $query) {
                $query->where('id', 2)->where('id', 3);
            })
            ->whereNull('deleted_at')
            ->equal('id', 1)
            ->lt('id', 12)
            ->orEqual('id', 10)
            ->gt('id', 3)
            ->orderBy('id', '')
            ->get();


        return view('admin.index.index');
    }
}