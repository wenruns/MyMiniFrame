<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 17:32
 */

namespace core\wen\models;


class Condition
{

    protected $_column = '';

    protected $_operator = '=';

    protected $_value = '';

    protected $_relation = 'and';

    public function __construct()
    {
    }

    public function column($column)
    {
        $this->_column = $column;
        return $this;
    }

    public function operator($operator)
    {
        $this->_operator = $operator;
        return $this;
    }

    public function value($value)
    {
        $this->_value = $value;
        return $this;
    }

    public function relation($relation)
    {
        $this->_relation = $relation;
        return $this;
    }
}