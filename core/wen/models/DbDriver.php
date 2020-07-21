<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:42
 */

namespace core\wen\models;


class DbDriver
{
    protected $_builder = null;

    public function __construct(SqlBuilder $builder)
    {
        $this->_builder = $builder;
    }


    public function pluck($column, $key)
    {

    }

    public function toSql()
    {
        return '';
    }
}