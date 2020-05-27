<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 11:24
 */

namespace src\xml;

trait MiddleWareTrait
{
    /**
     * @var array
     * ip白名单
     */
    protected $IP_LIST = [
//        '10.1.6.191'
    ];

    /**
     * @var array 时间段设置
     */
    protected $TIME_LIMIT = [
//        'start' => '12:00:00',
//        'end' => '13:00:00'
    ];

    /**
     * 检测是否在时间段内访问
     */
    protected function checkTime()
    {
        /**
         * 判断是否启用时间段限制
         */
        if (!empty($this->TIME_LIMIT)) {
            if (isTrue($this->TIME_LIMIT, 'start') && isTrue($this->TIME_LIMIT, 'end')) {
                $start = date('Y-m-d ').$this->TIME_LIMIT['start'];
                $end = date('Y-m-d ').$this->TIME_LIMIT['end'];
                if (time() < strtotime($start) || time() > strtotime($end)) {
                    apiResponse('不在允许时间段内访问');
                }
            } else if(isTrue($this->TIME_LIMIT, 'start')) {
                $start = date('Y-m-d ').$this->TIME_LIMIT['start'];
                if (time() < strtotime($start)) {
                    apiResponse('不在允许时间段内访问');
                }
            } else if (isTrue($this->TIME_LIMIT, 'end')) {
                $end = date('Y-m-d ').$this->TIME_LIMIT['end'];
                if (time() > strtotime($end)) {
                    apiResponse('不在允许时间段内访问');
                }
            }
        }
    }

    /**
     * 检测是否启用IP白名单
     */
    protected function checkIp()
    {
        $ip = get_client_IP();
        if (!empty($this->IP_LIST) && !in_array($ip, $this->IP_LIST)) {
            apiResponse('非法请求');
        }
    }
}