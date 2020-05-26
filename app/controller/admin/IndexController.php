<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/21
 * Time: 11:20
 */

namespace app\controller\admin;

use app\controller\Controller;
use wenruns\apple\ipa\install\MobileConfig;

class IndexController extends Controller
{
    public function index()
    {


        $mobileConfig = new MobileConfig();
        $res = $mobileConfig->redirectUrl('1')
            ->organization('2')
            ->appName('465')
            ->subDir('wen')
            ->create();
        if ($res['status']) {
//            $rst = $mobileConfig->sign($res['mobileconfig']);
//            $mobileConfig->download($res['mobileconfig']);
        }
        dd($res);
    }
}