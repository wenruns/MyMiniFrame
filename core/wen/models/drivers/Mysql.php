<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:27
 */

namespace core\wen\models\drivers;

use core\wen\models\Query;

class Mysql extends Drivers
{
    protected $sql = '';

    protected $flag = true;


    protected function buildSql($info)
    {
        // TODO: Implement buildSql() method.
        switch ($info['operator']) {
            case 'select':
                return $this->makeSelectSql($info);
                break;
            case 'update':
                break;
            case 'delete':
                break;
            case 'insert':
                break;
            default:
                return $this->makeSubCondition($info);
        }
    }

    protected function makeSubCondition($info)
    {
        $this->condition($info['conditions']);
        return trim($this->sql);
    }

    protected function makeSelectSql($info)
    {
        $fields = $info['selects'];
        $fields = empty($fields) ? '*' : (is_array($fields) ? implode(',', $fields) : $fields);
        $table = $info['table'];
        $aliasName = $info['aliasName'];
        $this->sql = "select $fields from $table " . ($aliasName ? "as $aliasName" : '');
        $this->relationShip($info['relationships']);
        $this->condition($info['conditions']);
        return trim($this->sql);
    }

    protected function relationShip($relations)
    {
        if (empty($relations)) {
            return;
        }
        foreach ($relations as $k => $relation) {
            $this->sql .= ' ' . $relation->toSql();
        }
    }

    protected function condition($conditions)
    {
        if (!empty($this->sql)) {
            $this->sql .= ' where ';
        }
        foreach ($conditions as $k => $condition) {
            $method = $condition->getMethod();
            $this->sql .= $this->$method(...$condition->getArguments());
        }
    }


    protected function where($column, $operator = '=', $value = '', $relation = 'and')
    {
        if (is_callable($column)) {
            $query = new Query($this->model);
            call_user_func($column, $query);
            $conditionSql = '(' . trim($query->toSql()) . ')';
        } else if (empty($value)) {
            $conditionSql = $column . ' = ?';
            $this->setValue($operator);
        } else {
            $conditionSql = $column . ' ' . $operator . ' ?';
            $this->setValue($value);
        }
        return $this->checkCondition($conditionSql, $relation);
    }


    protected function orWhere($column, $operator = '=', $value = '')
    {
        return $this->where($column, $operator, $value, 'or');
    }


    protected function whereNull($column)
    {
        return $this->where($column, 'is', 'null', 'and');
    }

    protected function orWhereNull($column)
    {
        return $this->where($column, 'is', 'null', 'or');
    }

    protected function equal($column, $value)
    {
        return $this->where($column, '=', $value, 'and');
    }

    protected function orEqual($column, $value)
    {
        return $this->where($column, '=', $value, 'or');
    }

    protected function lt($column, $value)
    {
        return $this->where($column, '<', $value, 'and');
    }

    protected function orLt($column, $value)
    {
        return $this->where($column, '<', $value, 'or');
    }


    protected function gt($column, $value)
    {
        return $this->where($column, '>', $value, 'and');
    }

    protected function orGt($column, $value)
    {
        return $this->where($column, '>', $value, 'or');
    }


    protected function whereNotNull($column)
    {
        return $this->where($column, 'is', 'not null', 'and');
    }

    protected function orWhereNotNull($column)
    {
        return $this->where($column, 'is', 'not null', 'or');
    }

    protected function like($column, $value)
    {
        return $this->where($column, 'like', $value, 'and');
    }

    protected function orLike($column, $value)
    {
        return $this->where($column, 'like', $value, 'or');
    }

    protected function regexp($column, $value)
    {
        return $this->where($column, 'regexp', $value, 'and');
    }

    protected function orRegexp($column, $value)
    {
        return $this->where($column, 'regexp', $value, 'or');
    }


    protected function between($column, $value = [], $relation = 'and')
    {
        $conditionSql = $column . ' between ? and ?';
        foreach ($value as $v) {
            $this->setValue($v);
        }
        return $this->checkCondition($conditionSql, $relation);
    }

    protected function orBetween($column, $value = [])
    {
        return $this->between($column, $value, 'or');
    }

    protected function having($column, $operator = '', $value = '', $relation = 'and')
    {
        $conditionSql = 'having ' . $column;
        if ($value) {
            $conditionSql . ' ' . $operator . ' ?';
            $this->setValue($value);
        }
        return $this->checkCondition($conditionSql, $relation);
    }

    protected function orHaving($column, $operator = '', $value = '')
    {
        return $this->having($column, $operator, $value, 'or');
    }

    protected function exist($subSql, $relation = 'and')
    {
        $conditionSql = 'exist ' . $subSql;
        return $this->checkCondition($conditionSql, $relation);
    }

    protected function orExist($subSql)
    {
        return $this->exist($subSql, 'or');
    }

    protected function notExist($subSql, $relation = 'and')
    {
        $conditionSql = 'not exist ' . $subSql;
        return $this->checkCondition($conditionSql, $relation);
    }

    protected function orNotExist($subSql)
    {
        return $this->notExist($subSql, 'or');
    }

    protected function checkCondition($conditionSql, $relation)
    {
        if ($this->flag) {
            $this->flag = false;
            return $conditionSql . ' ';
        }
        return $relation . ' ' . $conditionSql . ' ';
    }

    public function exec($sql)
    {
        // TODO: Implement exec() method.
        $values = $this->values;
        mysqli_prepare($this->connection->handle(), $sql);
        $sql = str_replace('?', '%s', $sql);
        dd(11, $sql, $this->values, $this->sql, sprintf($sql, ...$values), $this->connection);
    }
}