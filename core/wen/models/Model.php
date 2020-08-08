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
 * @method $this get($fields = null)
 * @method $this pluck($value_column, $key_column)
 * @method $this first($fields = null)
 * @method $this save($data)
 * @method $this update($data)
 * @method $this select($fields)
 * @method $this alias($aliasName)
 * @method Join fullJoin($table, $primaryKey, $operator = '=', $foreignKey = '', $aliasName = '')
 * @method Join rightJoin($table, $primaryKey, $operator = '=', $foreignKey = '', $aliasName = '')
 * @method Join leftJoin($table, $primaryKey, $operator = '=', $foreignKey = '', $aliasName = '')
 * @method Join join($table, $primaryKey, $operator = '=', $foreignKey = '', $aliasName = '')
 * @method Join innerJoin($table, $primaryKey, $operator = '=', $foreignKey = '', $aliasName = '')
 * @method $this transaction(\Closure $closure)
 * @method $this groupBy($column)
 * @method $this orderBy($column, $order = 'asc')
 * @method $this offset($startLine)
 * @method $this limit($rows)
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
     * @var string
     */
    protected $prefix = '';

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
    protected $connection = '';


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
     * @param string $sql
     * @return mixed
     * @throws \Exception
     */
    public function execSql($sql = '')
    {

//        $this->getPDO()->
        return $this->getBuilder();
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
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return array
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->origin;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->origin[$name];
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->origin[$name] = $value;
    }
}