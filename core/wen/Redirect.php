<?php
/**
 * 重定向
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/9
 * Time: 10:03
 */

namespace core\wen;


class Redirect
{
    protected $_toUrl = '';

    protected $_fromUrl = '';

    protected $_params = [];

    protected $_code = 303;

    public function __construct($url = '', $params = [], $code = 303)
    {
        $this->_toUrl = $url;
        $this->_params = $params;
        $this->_code = $code;
//        $this->_fromUrl =
    }

    public function to($url, $params = [], $code = 303)
    {
        $this->_toUrl = $url;
        $this->_params = $params;
        $this->_code = $code;
        $this->run();
    }

    public function run()
    {
        http_redirect($this->_toUrl, $this->_params, true, $this->_code);
    }

}