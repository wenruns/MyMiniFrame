<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:52
 */

namespace core\wen\models;

/**
 * @package core\wen\models
 *
 * @method $this where($column, $operator = '', $value = '')
 *
 * @method $this orWhere($column, $operator = '', $value = '')
 *
 * @method $this whereNull($column)
 *
 * @method $this orWhereNull($column)
 *
 * @method $this whereNotNull($column)
 *
 * @method $this orWhereNotNull($column)
 *
 * @method $this regexp($column, $reg)
 *
 * @method $this orRegexp($column, $reg)
 *
 * @method $this like($column, $value)
 *
 * @method $this orLike($column, $value)
 *
 * @method $this eq($column, $value)
 *
 * @method $this orEq($column, $value)
 *
 * @method $this lt($column, $value)
 *
 * @method $this orLt($column, $value)
 *
 * @method $this gt($column, $value)
 *
 * @method $this orGt($column, $value)
 *
 * @method $this between($column, $range = [])
 *
 * @method $this orBetween($column, $range = [])
 *
 * @method $this having($column, $operator = '', $value = '')
 *
 * @method $this orHaving($column, $operator = '', $value = '')
 *
 * @method $this exist($subSql)
 *
 * @method $this notExist($subSql)
 *
 */
class Query
{

    protected $sql = '';

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var null
     */
    protected $sqlBuilder = null;

    /**
     * @var array
     */
    protected $methods = [
        'where',
        'orWhere',
        'whereNull',
        'orWhereNull',
        'whereNotNull',
        'orWhereNotNull',
        'regexp',
        'orRegexp',
        'like',
        'orLike',
        'equal',
        'orEqual',
        'lt',
        'orLt',
        'gt',
        'orGt',
        'between',
        'orBetween',
    ];

    /**
     * @return SqlBuilder|null
     * @throws \Exception
     */
    protected function getBuilder()
    {
        if (empty($this->sqlBuilder)) {
            $this->sqlBuilder = new SqlBuilder($this);
        }
        return $this->sqlBuilder;
    }

    public function buildSql()
    {
        $this->sql = $this->getBuilder()->toSql();
        return $this;
    }


    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (in_array($name, $this->methods)) {
            $this->conditions[$name][] = new Condition($name, $arguments);
            return $this;
        } else {
            return $this->$name(...$arguments);
        }
    }

}