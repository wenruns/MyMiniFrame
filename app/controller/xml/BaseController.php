<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/29
 * Time: 15:53
 */

namespace app\controller\xml;


use core\wen\traits\FileTrait;

Class BaseController
{

    use MiddleWareTrait, FileTrait;

    public function __construct()
    {
        date_default_timezone_set('PRC');
        $this->TIME_LIMIT = config('config.time_limit', []);
        $this->IP_LIST = config('config.ip_list', []);
        $this->checkTime();
        $this->checkIp();
    }

    /**
     * @param $str
     * @param string $key
     * @param string $iv
     * @param string $method
     * @return string
     *
     * 解密
     */
    public function decrypt($str, $key = '', $iv = '', $method = 'aes-256-cbc')
    {
        return openssl_decrypt(base64_decode(openssl_decrypt($str, $method, $key, OPENSSL_RAW_DATA, $iv)), $method, $key, OPENSSL_RAW_DATA, $iv);
    }

}