<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:55
 */

namespace core\wen\models;


use core\wen\App;

trait MethodTrait
{
    protected $sqlBuilder = null;

    /**
     * @return SqlBuilder|null
     * @throws \Exception
     */
    protected function getBuilder()
    {
        if (empty($this->sqlBuilder)) {
            $this->sqlBuilder = new SqlBuilder($this);
//            $this->sqlBuilder = App::make(SqlBuilder::class, [$this]);
        }
        return $this->sqlBuilder;
    }

    public function where($column, $operator = '=', $value = '')
    {
        if (is_callable($column)) {
            $this->getBuilder()->addNestedWhereQuery($column, 'and');
        } else {
            $this->getBuilder()->where(...func_get_args());
        }
        return $this;
    }

    public function orWhere($column, $operator = '=', $value = '')
    {
        if (is_callable($column)) {
            $this->getBuilder()->addNestedWhereQuery($column, 'or');
        } else {
            $this->getBuilder()->where($column, $operator, $value, 'or');
        }
        return $this;
    }

    public function whereNull($column)
    {
        $this->getBuilder()->where($column, 'is', 'null');
        return $this;
    }

    public function orWhereNull($column)
    {
        $this->getBuilder()->where($column, 'is', 'null', 'or');
        return $this;
    }

    public function whereNotNull($column)
    {
        $this->getBuilder()->where($column, 'is not', 'null');
        return $this;
    }

    public function orWhereNotNull($column)
    {
        $this->getBuilder()->where($column, 'is not', 'null', 'or');
        return $this;
    }

    public function regexp($column, $reg)
    {
        $this->getBuilder()->where($column, 'regexp', $reg);
        return $this;
    }

    public function orRegexp($column, $reg)
    {
        $this->getBuilder()->where($column, 'regexp', $reg, 'or');
        return $this;
    }


    public function like($column, $value)
    {
        $this->getBuilder()->where($column, 'like', $value);
        return $this;
    }

    public function orLike($column, $value)
    {
        $this->getBuilder()->where($column, 'like', $value, 'or');
        return $this;
    }

    public function eq()
    {

    }

    public function orEq()
    {

    }

    public function lt()
    {

    }

    public function orLt()
    {

    }

    public function gt()
    {

    }

    public function orGt()
    {

    }


    public function toSql()
    {
        return $this->getBuilder()->toSql();
    }

}