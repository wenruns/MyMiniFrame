<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:21
 */

namespace core\wen\models;

class SqlBuilder
{

    protected $_conditions = [];

    protected $_query = null;

    protected $_driver = null;

    protected function getDriver()
    {
        if (empty($this->_driver)) {
            $this->_driver = new DbDriver($this);
        }
        return $this->_driver;
    }

    protected function condition()
    {
        return new Condition();
    }

    public function __construct($query)
    {
        $this->_query = $query;
    }


    public function addNestedWhereQuery(\Closure $closure, $relation = 'and')
    {
        $this->_conditions[] = $this->condition()->relation($relation)->column($closure);
        return $this;
    }


    public function where($column, $operator, $value, $relation = 'and')
    {
        $this->_conditions[] = $this->condition()
            ->column($column)
            ->operator($operator)
            ->value($value)
            ->relation($relation);
        return $this;
    }

    public function getConditions()
    {
        return $this->_conditions;
    }

    public function getModel()
    {
        if ($this->_query instanceof Model) {
            return $this->_query;
        }
        return null;
    }

    public function toSql()
    {
        $this->getDriver()->toSql();
    }
}