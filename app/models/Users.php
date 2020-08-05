<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/20
 * Time: 9:27
 */

namespace app\models;

use core\wen\models\Model;

class Users extends Model
{

    protected $table = 'admin_users';

    protected $primaryKey = 'id';

    protected $connection = 'old_mysql';

}