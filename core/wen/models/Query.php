<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:52
 */

namespace core\wen\models;

use core\wen\App;
use core\wen\models\pdo\PDO;

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

    /**
     * @var PDO|null
     */
    protected $pdo = null;
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var null
     */
    protected $sqlBuilder = null;

    /**
     * @var null|\Exception
     */
    protected $exception = null;


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
        'select',
        'alias',
        'groupBy',
        'orderBy',
        'offset',
        'limit'
    ];

    protected $relations = [
        'fullJoin',
        'rightJoin',
        'leftJoin',
        'innerJoin',
        'join',
    ];

    protected $operator = [
        'transaction',
        'get',
        'pluck',
        'first',
        'update',
        'delete',
        'save',
    ];

    /**
     * @param $attrName
     * @param $value
     * @return $this
     */
    protected function setAttr($attrName, $value)
    {
        $this->$attrName = $value;
        return $this;
    }

    /**
     * @param $attrName
     * @param null $default
     * @return |null
     */
    protected function getAttr($attrName, $default = null)
    {
        return isset($this->$attrName) ? $this->$attrName : $default;
    }

    /**
     * @return SqlBuilder|null
     * @throws \Exception
     */
    public function getBuilder()
    {
        if (empty($this->sqlBuilder)) {
            $this->sqlBuilder = new SqlBuilder($this);
        }
        return $this->sqlBuilder;
    }

    public function __construct(Query $model = null)
    {
        $this->model = $model;
    }

    /**
     * @param \Exception $e
     * @return $this
     */
    public function exception(\Exception $e)
    {
        $this->exception = $e;
        return $this;
    }

    public function getErrorInfo()
    {
        if (empty($this->exception)) {
            return null;
        }
        return [
            'errorCode' => $this->exception->getCode(),
            'errorMsg' => $this->exception->getMessage(),
        ];
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function addValues($name, $value)
    {
        if ($this->model) {
//            $this->getBuilder()->getDriver()->addValues($value);
            $this->model->getBuilder()->addValue($name, $value);
        } else {
            $this->getBuilder()->addValue($name, $value);
        }
        return $this;
    }


    /**
     * @return SqlBuilder|null
     * @throws \Exception
     */
    public function toSql()
    {
        return $this->getBuilder();
    }


    /**
     * @return PDO|mixed|null
     * @throws \Exception
     */
    protected function getPDO()
    {
        if (empty($this->pdo)) {
            $this->pdo = App::make(PDO::class, [$this->getBuilder()]);
        }
        return $this->pdo;
    }

    public function buildSql(Join $join = null)
    {
        return $this->getBuilder()->isJoin($join)->buildSql();
    }


    /**
     * @param $name
     * @param $arguments
     * @return $this|Join|mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        ($rst = $this->isMethod($name, $arguments))
        || ($rst = $this->isOperator($name, $arguments))
        || ($rst = $this->isRelation($name, $arguments))
        || ($rst = $this->checkMethod($name, $arguments));

        if ($rst === false) {
            throw new \Exception('method::' . $name . ' is not defined.');
        }
        return $rst;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    protected function checkMethod($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        } else if ($this->model && method_exists($this->model, $name)) {
            return $this->model->$name(...$arguments);
        }
        return false;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool|Join
     */
    protected function isRelation($name, $arguments)
    {
        if (in_array($name, $this->relations)) {
            $table = $arguments[0];
            $primaryKey = $arguments[1];
            $operator = isset($arguments[2]) ? $arguments[2] : '=';
            $foreignKey = isset($arguments[3]) ? $arguments[3] : '';
            $aliasName = isset($arguments[4]) ? $arguments[4] : '';
            $join = new Join($this, $table);
            $join->on($primaryKey, $operator, $foreignKey)->alias($aliasName)->method(str_replace('join', '', strtolower($name)));
            $this->conditions[] = new Condition($name, $join);
            return $join;
        }
        return false;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this|bool
     * @throws \Exception
     */
    protected function isOperator($name, $arguments)
    {
        if (in_array($name, $this->operator)) {
            $result = $this->getPdo()->$name(...$arguments);
            $this->setAttr('origin', $result);
            return $this;
        }
        return false;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this|bool
     */
    protected function isMethod($name, $arguments)
    {
        if (in_array($name, $this->methods)) {
            $this->conditions[] = new Condition($name, $arguments);
            return $this;
        }
        return false;
    }

    /**
     * @return Query
     */
    protected static function getInstance()
    {
        return new static();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return self::getInstance()->$name(...$arguments);
    }

}