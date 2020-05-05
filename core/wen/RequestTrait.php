<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 8:49
 */
namespace core\wen;

trait RequestTrait
{
    public function request($index = '', $default = null)
    {
        $params = $this->params();
        $index = explode('.', $index);
        foreach ($index as $key) {
            if (!isset($params[$key])) {
                return $default;
            }
            $params = $params[$key];
        }
        return $params;
    }


    protected function params()
    {
        return array_merge($_POST, $_GET);
    }
}