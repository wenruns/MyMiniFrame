<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:22
 */

namespace core\wen\models;

/**
 * Class Model
 * @package core\wen\models
 *
 */
class Model extends Query
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $origin = [];

    /**
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * @var array
     */
    protected $relationships = [];

    /**
     * @var string
     */
    protected $aliasName = '';


    protected $operator = 'select';

    /**
     * @throws \Exception
     */
    protected function initConnect()
    {
        if (empty($this->connection)) {
            $this->connection = config('database.default', 'mysql');
        }
    }

    /**
     * @throws \ReflectionException
     */
    protected function initTable()
    {
        if (empty($this->table)) {
            $reflection = new \ReflectionClass($this);
            $modelName = $reflection->getName();
            $tableName = mb_substr($modelName, strrpos($modelName, '\\') + 1);
            $this->table = trim(strtolower(preg_replace('/(.*?)([A-Z])/', '${1}_${2}', $tableName)), '_');
        }
    }

    /**
     * @param $fields
     * @return $this
     */
    protected function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Model constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->initConnect();
        $this->initTable();
    }


    /**
     *
     */
    public function get()
    {
        return $this->setAttr('operator', 'select')->buildSql()->execSql();
    }


    protected function setAttr($attrName, $value)
    {
        $this->$attrName = $value;
        return $this;
    }

    protected function getAttr($attrName, $default = null)
    {
        return isset($this->$attrName) ? $this->$attrName : $default;
    }

    /**
     * @param $column
     * @param $key
     * @throws \Exception
     */
    public function pluck($column, $key)
    {
        $this->getBuilder()->getDriver()->pluck($column, $key);
    }


    /**
     *
     */
    public function first()
    {

    }

    /**
     *
     */
    public function save()
    {

    }

    /**
     * @param $data
     * @param array $conditionFields
     */
    public function update($data, $conditionFields = [])
    {

    }

    /**
     * @param $rows
     */
    public function limit($rows)
    {

    }

    /**
     * @param $line
     */
    public function offset($line)
    {

    }

    /**
     * @param $column
     * @param string $order
     * @return $this
     */
    public function orderBy($column, $order = 'asc')
    {
//        dd($column, $order);
        return $this;
    }


    /**
     * @param $column
     * @return $this
     */
    public function groupBy($column)
    {
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function transaction(\Closure $closure)
    {
        return call_user_func(function (Model $model) use ($closure) {
            // todo:: 启动事务
            try {
                $res = call_user_func($closure, $model);
                // todo:: 提交事务
            } catch (\Exception $e) {
                // todo:: 回滚事务
                throw new \Exception($e);
            }
            return $res;
        }, $this);
    }


    /**
     * @param string $sql
     * @return mixed
     * @throws \Exception
     */
    public function execSql($sql = '')
    {
        $sql && $this->sql = $sql;
        return $this->getBuilder()->exec($this->sql);
    }


    /**
     * @param $table
     * @param $primaryKey
     * @param $foreignKey
     * @param string $operator
     * @param string $aliasName
     * @return $this
     */
    public function join($table, $primaryKey, $foreignKey, $operator = '=', $aliasName = '')
    {
        $this->addRelationship($table)->on($primaryKey, $foreignKey, $operator)->alias($aliasName);
        return $this;
    }

    /**
     * @param $table
     * @param $primaryKey
     * @param $foreignKey
     * @param string $operator
     * @param string $aliasName
     * @return $this
     */
    public function leftJoin($table, $primaryKey, $foreignKey, $operator = '=', $aliasName = '')
    {
        $this->addRelationship($table)->on($primaryKey, $foreignKey, $operator)->alias($aliasName)->method('left');
        return $this;
    }

    /**
     * @param $table
     * @param $primaryKey
     * @param $foreignKey
     * @param string $operator
     * @param string $aliasName
     * @return $this
     */
    public function rightJoin($table, $primaryKey, $foreignKey, $operator = '=', $aliasName = '')
    {
        $this->addRelationship($table)->on($primaryKey, $foreignKey, $operator)->alias($aliasName)->method('right');
        return $this;
    }

    /**
     * @param $table
     * @param $primaryKey
     * @param $foreignKey
     * @param string $operator
     * @param string $aliasName
     * @return $this
     */
    public function fullJoin($table, $primaryKey, $foreignKey, $operator = '=', $aliasName = '')
    {
        $this->addRelationship($table)->on($primaryKey, $foreignKey, $operator)->alias($aliasName)->method('full');
        return $this;
    }

    /**
     * @param $table
     * @return Join
     */
    protected function addRelationship($table)
    {
        $join = new Join($this, $table);
        $this->relationships[] = $join;
        return $join;
    }


    public function alias($aliasName)
    {
        $this->aliasName = $aliasName;
        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    public function select($fields)
    {
        $this->setFields($fields);
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return array
     */
    public function getRelationships()
    {
        return $this->relationships;
    }

    /**
     * @return string
     */
    public function getAliasName()
    {
        return $this->aliasName;
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.

    }
}