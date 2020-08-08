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
    protected $method = '';

    protected $arguments = [];


    public function __construct($method, $arguments)
    {
        $this->method = $method;
        $this->arguments = $arguments;
    }


    public function getMethod()
    {
        return $this->method;
    }

    public function getArguments()
    {
        return $this->arguments;
    }


    public function isAlias($closure)
    {
        if ($this->method == 'alias') {
            call_user_func($closure, ...$this->arguments);
        }
        return $this;
    }

    public function isRelation($closure)
    {
        if (in_array($this->method, [
            'join', 'leftJoin', 'rightJoin', 'fullJoin', 'innerJoin'
        ])) {
            call_user_func($closure, $this->arguments);
        }
        return $this;
    }

    public function isCondition($closure)
    {
        if (in_array($this->method, [
            'where', 'orWhere', 'whereNull', 'orWhereNull', 'whereNotNull', 'orWhereNotNull', 'regexp', 'orRegexp',
            'like', 'orLike', 'equal', 'orEqual', 'lt', 'orLt', 'gt', 'orGt', 'between', 'orBetween',
        ])) {
            call_user_func($closure, $this->method, $this->arguments);
        }
        return $this;
    }

    public function isSelect($closure)
    {
        if ($this->method == 'select') {
            call_user_func($closure, ...$this->arguments);
        }
        return $this;
    }

    public function isLimit($closure)
    {
        if ($this->method == 'limit') {
            call_user_func($closure, ...$this->arguments);
        }
        return $this;
    }


    public function isOffset($closure)
    {
        if ($this->method == 'offset') {
            call_user_func($closure, ...$this->arguments);
        }
        return $this;
    }

    public function isGroupBy($closure)
    {
        if ($this->method == 'groupBy') {
            call_user_func($closure, $this->arguments);
        }
        return $this;
    }

    public function isOrderBy($closure)
    {
        if ($this->method == 'orderBy') {
            call_user_func($closure, ...$this->arguments);
        }
        return $this;
    }
}