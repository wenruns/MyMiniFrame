<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:22
 */

namespace core\wen\models;

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
    protected $connect = 'mysql';

    protected $joins = [];

    protected $aliasName = '';

    /**
     * @throws \Exception
     */
    protected function initConnect()
    {
        if (empty($this->connect)) {
            $this->connect = config('database.default', 'mysql');
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
        $this->buildSql()->execSql();
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
     *
     */
    public function update()
    {

    }

    public function limit($rows)
    {

    }

    public function offset($line)
    {

    }

    public function orderBy($column, $order = 'asc')
    {
//        dd($column, $order);
        return $this;
    }


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
        return $this->getBuilder()->exec($sql);
    }


    /**
     * @param $table
     * @param $primaryKey
     * @param string $foreignKey
     * @param string $operator
     * @param string $aliasName
     * @return Join
     */
    public function join($table, $primaryKey, $foreignKey = '', $operator = '=', $aliasName = '')
    {
        return $this->addRelationship($table)->on($primaryKey, $foreignKey, $operator)->alias($aliasName);
    }

    /**
     * @param $table
     * @return Join
     */
    protected function addRelationship($table)
    {
        $join = new Join($this, $table);
        $this->joins[] = $join;
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


    public function getTable()
    {
        return $this->table;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }


}