<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/21
 * Time: 16:04
 */

namespace app\models;


use core\wen\models\Model;

class AdminUserPermissions extends Model
{
    protected $table = 'admin_user_permissions';

    protected $primaryKey = 'user_id';
}