<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/21
 * Time: 15:11
 */

namespace core\wen\models;


/**
 * Class Join
 * @package core\wen\models
 *
 * @method Model where($column, $operator = '', $value = '')
 *
 * @method Model orWhere($column, $operator = '', $value = '')
 *
 * @method Model whereNull($column)
 *
 * @method Model orWhereNull($column)
 *
 * @method Model whereNotNull($column)
 *
 * @method Model orWhereNotNull($column)
 *
 * @method Model regexp($column, $reg)
 *
 * @method Model orRegexp($column, $reg)
 *
 * @method Model like($column, $value)
 *
 * @method Model orLike($column, $value)
 *
 * @method Model eq($column, $value)
 *
 * @method Model orEq($column, $value)
 *
 * @method Model lt($column, $value)
 *
 * @method Model orLt($column, $value)
 *
 * @method Model gt($column, $value)
 *
 * @method Model orGt($column, $value)
 *
 * @method Model between($column, $range = [])
 *
 * @method Model orBetween($column, $range = [])
 *
 * @method Model having($column, $operator = '', $value = '')
 *
 * @method Model orHaving($column, $operator = '', $value = '')
 *
 * @method Model exist($subSql)
 *
 * @method Model notExist($subSql)
 *
 * @method get()
 *
 * @method pluck($column, $key)
 *
 * @method first()
 *
 * @method save()
 *
 * @method update($data = [])
 *
 * @method Model limit($rows)
 *
 * @method Model offset($line)
 *
 * @method Model orderBy($column, $order = 'asc') return Model
 *
 * @method Model groupBy($column)
 *
 */
class Join
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var Model|null
     */
    protected $model = null;

    /**
     * @var string
     */
    protected $aliasName = '';

    /**
     * @var array
     */
    protected $condition = [];

    /**
     * @var array
     */
    protected $fields = [];


    protected $method = 'inner';

    protected $sql = '';


    protected function getTable()
    {
        if (gettype($this->table) == 'string') {
            return $this->table;
        }

        if ($this->table instanceof Model) {
            return $this->table->getTable();
        }
        $reflection = new \ReflectionClass($this->table);
        if ($reflection->isInstantiable() && ($instance = $reflection->newInstance()) instanceof Model) {
            return $instance->getTable();
        }
        throw new \Exception('join:table is not allowed!');
    }

    /**
     * Join constructor.
     * @param Model $model
     * @param $table
     */
    public function __construct(Model $model, $table)
    {
        $this->model = $model;
        $this->table = $table;
//        dd($this->getTable());
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $primaryKey
     * @param string $foreignKey
     * @param string $operator
     * @return $this
     */
    public function on($primaryKey, $operator = '=', $foreignKey = '')
    {
        if (is_callable($primaryKey)) {
            $query = new Query();
            call_user_func($primaryKey, $query);
            $this->condition[] = [
                'primaryKey' => $query,
            ];
        } else {
            $this->condition[] = [
                'primaryKey' => $primaryKey,
                'foreignKey' => $foreignKey,
                'operator' => $operator,
            ];
        }
        return $this;
    }


    /**
     * @param $aliasName
     * @return $this
     */
    public function alias($aliasName)
    {
        $this->aliasName = $aliasName;
        return $this;
    }

    public function method($method = 'left')
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this->model->$name(...$arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->$name;
    }


    public function toSql()
    {
        if (empty($this->sql)) {
            $table = trim($this->getTable());
            $this->sql = $this->method . ' join ' . $table . ($this->aliasName ? ' as ' . $this->aliasName : '') . ' on ';
            $this->condition();
        }
        return $this->sql;
    }

    protected function condition()
    {
        foreach ($this->condition as $item) {
            if ($item['primaryKey'] instanceof Query) {
                $sql = $item['primaryKey']->buildSql($this) . ' ';
//                $values = $item['primaryKey']->getBuilder()->getValues();
//                foreach ($values as $kk => $val) {
////                    $sql = preg_replace('/' . $kk . '( |\))/', $val . '${1}', $sql);
//                    $this->model->addValues($kk, $val);
//                }
                $this->sql .= $sql;
            } else {
                $this->sql .= $item['primaryKey'] . ' ' . $item['operator'] . ' ' . $item['foreignKey'] . ' and ';
            }
        }
        $this->sql = preg_replace('/and $/', '', $this->sql);
        return $this;
    }


}