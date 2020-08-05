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
 * @method $this equal($column, $value)
 *
 * @method $this orEqual($column, $value)
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
 * @method $this orHaving($column, $operator = '=', $value = '')
 *
 * @method $this exist($subSql)
 *
 * @method $this orExist($subSql)
 *
 * @method $this notExist($subSql)
 *
 * @method $this orNotExist($subSql)
 *
 */
class Query
{
    /**
     * @var Model|null
     */
    protected $model = null;

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

    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    public function buildSql()
    {
        $this->sql = $this->getBuilder()->toSql();
        return $this;
    }


    public function getConditions()
    {
        return $this->conditions;
    }

    public function addValues($value)
    {
        if ($this->model) {
//            $this->getBuilder()->getDriver()->addValues($value);
            $this->model->getBuilder()->getDriver()->addValues($value);
        } else {
            $this->getBuilder()->getDriver()->addValues($value);
        }
        return $this;
    }

    public function toSql()
    {
        return $this->getBuilder()->toSql();
    }

    public function getSqlInfos()
    {
        return [
            'table' => $this->call_function('getTable'),
            'selects' => $this->call_function('getFields'),
            'primaryKey' => $this->call_function('getPrimaryKey'),
            'aliasName' => $this->call_function('getAliasName'),
            'conditions' => $this->getConditions(),
            'relationships' => $this->call_function('getRelationships'),
            'operator' => $this->call_function('getAttr', ['operator'])
        ];
    }

    protected function call_function($methodName, $arguments = [])
    {
        if (method_exists($this, $methodName)) {
            return $this->$methodName(...$arguments);
        }
        return null;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (in_array($name, $this->methods)) {
            $this->conditions[] = new Condition($name, $arguments);
            return $this;
        } else {
            if (method_exists($this, $name)) {
                return $this->$name(...$arguments);
            } else if ($this->model && method_exists($this->model, $name)) {
                return $this->model->$name(...$arguments);
            }
        }
        throw new \Exception('method::' . $name . ' is not defined.');
    }

}