<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:21
 */

namespace core\wen\models;

class SqlBuilder
{
    /**
     * @var Query|null
     */
    protected $_model = null;

    /**
     * @var string
     */
    protected $sql = '';

    /**
     * @var bool
     */
    protected $flag = true;

    /**
     * @var array
     */
    protected $values = [];


    protected $aliasName = '';

    protected $join = '';

    protected $fields = '*';

    protected $groupBy = '';

    protected $orderBy = '';

    protected $limit = '';

    protected $offset = '';

    protected $condition = '';

    protected $operator = null;

    /**
     * @var null|Join
     */
    protected $joinObj = null;


    /**
     * @var array
     */
    static $maps = [];

    /**
     * @var array
     */
    static $numbers = [];


    /**
     * SqlBuilder constructor.
     * @param $query
     */
    public function __construct(Query $query)
    {
        $this->_model = $query;
    }

    /**
     * @param Join $join
     * @return $this
     */
    public function isJoin(Join $join = null)
    {
        $this->joinObj = $join;
        return $this;
    }

    /**
     * @return Query|Model|null
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->getModel()->getFields();
    }

    /**
     * @return array
     */
    public function getMaps()
    {
        return self::$maps;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }


    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addValue($name, $value)
    {
        $this->values[$name] = $value;
        return $this;
    }


    /**
     * @param $values
     * @return $this
     */
    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }

    public function initData()
    {
        $this->sql = '';
        $this->values = [];
        $this->flag = true;
        $this->aliasName = '';
        $this->join = '';
        $this->fields = '*';
        $this->groupBy = '';
        $this->orderBy = '';
        $this->limit = '';
        $this->offset = '';
        $this->operator = null;
        $this->condition = '';
    }


    /**
     * @param string $operator
     * @return string
     */
    public function buildSql($operator = null)
    {
        $this->operator = $operator;
        // TODO: Implement buildSql() method.
        switch ($operator) {
            case 'select':
                $res = $this->makeSelectSql();
                break;
            case 'update':
                $res = true;
                break;
            case 'delete':
                $res = true;
                break;
            case 'insert':
                $res = $this->makeInsertSql();
                break;
            default:
                $res = $this->makeSubCondition();
        }
        return $res;
    }

    protected function makeInsertSql()
    {
        $this->sql = 'insert into ' . $this->getTable() . ' ';
        $fieldStr = '(';
        $valueStr = '(';
        foreach ($this->values as $field => $value) {
            $fieldStr .= '`' . $field . '`,';
            $valueStr .= ':' . $field . ',';
        }
        $valueStr = rtrim($valueStr, ',') . ')';
        $fieldStr = rtrim($fieldStr, ',') . ')';

        $this->sql .= $fieldStr . ' values ' . $valueStr;
//        dd($this->sql);
        return $this->sql;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     * @throws \Exception
     */
    protected function setValue($name, $value)
    {
        if ($this->joinObj) {
            $this->joinObj->getModel()->addValues($name, $value);
        } else {
            $this->getModel()->addValues($name, $value);
        }
        return $this;
    }


    /**
     * @param $info
     * @return string
     */
    protected function makeSubCondition()
    {
        collect($this->getModel()->getConditions())->each(function (Condition $item) {
            $item->isCondition(array($this, 'condition'));
        });
        return trim(trim(trim($this->condition), 'where'));
    }


    protected function getTable()
    {
        $prefix = $this->getModel()->getPrefix();
        if (empty($prefix)) {
            $prefix = config('database.connections.' . $this->getModel()->getConnection() . '.prefix', '');
        }
        return $prefix . $this->getModel()->getTable();
    }

    /**
     * @param $info
     * @return string
     */
    protected function makeSelectSql()
    {
        $conditions = $this->getModel()->getConditions();
        collect($conditions)->each(function (Condition $item) {
            $item->isAlias(array($this, 'alias'))
                ->isRelation(array($this, 'relation'))
                ->isSelect(array($this, 'select'))
                ->isCondition(array($this, 'condition'))
                ->isGroupBy(array($this, 'groupBy'))
                ->isOrderBy(array($this, 'orderBy'))
                ->isLimit(array($this, 'limit'))
                ->isOffset(array($this, 'offset'));
        });

        $this->sql = 'select ' . $this->fields . ' from ' . $this->getTable() . $this->aliasName . $this->join . $this->condition . $this->groupBy . $this->orderBy . $this->limit . $this->offset;

        return trim($this->sql);
    }

    public function offset($startLine)
    {
        $this->offset = " offset $startLine";
        return $this;
    }

    public function limit($rows)
    {
        $this->limit = " limit $rows";
        return $this;
    }

    public function orderBy($column, $order)
    {
        if (empty($this->orderBy)) {
            $this->orderBy = " order by $column $order";
        } else {
            $this->orderBy .= ",$column $order";
        }
        return $this;
    }

    public function groupBy($columns)
    {
        if (empty($this->groupBy)) {
            $this->groupBy = " group by " . implode(',', $columns);
        } else {
            $this->groupBy .= "," . implode(',', $columns);
        }
        return $this;
    }

    public function select($fields)
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        $this->fields = $fields;
        return $this;
    }

    public function alias($aliasName)
    {
        $this->aliasName = ' as ' . $aliasName;
        return $this;
    }

    public function relation(Join $join)
    {
        if (empty($this->join)) {
            $this->join = ' ' . $join->toSql();
        } else {
            $this->join .= $join->toSql();
        }
        return $this;
    }


    /**
     * @param $conditions
     */
    public function condition($method, $conditions)
    {
        if (empty($this->condition)) {
            $this->condition = ' where ';
        }
        $this->condition .= $this->$method(...$conditions);
    }


    /**
     * @param $column
     * @param string $operator
     * @param string $value
     * @param string $relation
     * @return string
     * @throws \Exception
     */
    protected function where($column, $operator = '=', $value = '', $relation = 'and')
    {
        if (is_callable($column)) {
            $query = new Query($this->getModel());
            call_user_func($column, $query);
            $conditionSql = '(' . trim($query->buildSql($this->joinObj)) . ')';
        } else if (empty($value)) {
            $name = $this->getSqlVariableName($column);
            $conditionSql = $column . ' = ' . $name;
            $this->setValue($column, $operator);
        } else {
            $name = $this->getSqlVariableName($column);
            $conditionSql = $column . ' ' . $operator . ' ' . $name;
            $this->setValue($column, $value);
        }
        return $this->checkCondition($conditionSql, $relation);
    }

    /**
     * @param $name
     * @return string
     */
    public function getSqlVariableName($name)
    {
        $name = ':' . str_replace('.', '', $name);
        if (isset(self::$numbers[$name])) {
            self::$numbers[$name]++;
            $_name = $name . self::$numbers[$name];
            self::$maps[$_name] = $name;
            return $_name;
        }
        self::$numbers[$name] = 0;
        self::$maps[$name] = $name;
        return $name;
    }


    /**
     * @param $column
     * @param string $operator
     * @param string $value
     * @return string
     */
    protected function orWhere($column, $operator = '=', $value = '')
    {
        return $this->where($column, $operator, $value, 'or');
    }


    /**
     * @param $column
     * @param string $relation
     * @return string
     */
    protected function whereNull($column, $relation = 'and')
    {
        $conditionSql = "$column is null";
        return $this->checkCondition($conditionSql, $relation);
//        return $this->where($column, 'is', 'null', 'and');
    }

    /**
     * @param $column
     * @return string
     */
    protected function orWhereNull($column)
    {
        return $this->whereNull($column, 'or');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function equal($column, $value)
    {
        return $this->where($column, '=', $value, 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function orEqual($column, $value)
    {
        return $this->where($column, '=', $value, 'or');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function lt($column, $value)
    {
        return $this->where($column, '<', $value, 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function orLt($column, $value)
    {
        return $this->where($column, '<', $value, 'or');
    }


    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function gt($column, $value)
    {
        return $this->where($column, '>', $value, 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function orGt($column, $value)
    {
        return $this->where($column, '>', $value, 'or');
    }


    /**
     * @param $column
     * @param string $relation
     * @return string
     */
    protected function whereNotNull($column, $relation = 'and')
    {
        $conditionSql = "$column is not null";
        return $this->checkCondition($conditionSql, $relation);
    }

    /**
     * @param $column
     * @return string
     */
    protected function orWhereNotNull($column)
    {
        return $this->whereNotNull($column, 'or');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function like($column, $value)
    {
        return $this->where($column, 'like', $value, 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function orLike($column, $value)
    {
        return $this->where($column, 'like', $value, 'or');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function regexp($column, $value)
    {
        return $this->where($column, 'regexp', $value, 'and');
    }

    /**
     * @param $column
     * @param $value
     * @return string
     */
    protected function orRegexp($column, $value)
    {
        return $this->where($column, 'regexp', $value, 'or');
    }

    /**
     * @param $column
     * @param array $value
     * @param string $relation
     * @return string
     */
    protected function between($column, $value = [], $relation = 'and')
    {
        if (empty($value)) {
            return '';
        }
        $startValue = array_shift($value);
        $startName = $this->getSqlVariableName('start');
        $this->setValue('start', $startValue);
        if (count($value >= 2)) {
            $endValue = array_shift($value);
            $endName = $this->getSqlVariableName('end');
            $this->setValue('end', $endValue);
            $conditionSql = "$column  between $startName and $endName";
        } else {
            $conditionSql = "$column >= " . $startName;
        }
        return $this->checkCondition($conditionSql, $relation);
    }

    /**
     * @param $column
     * @param array $value
     * @return string
     */
    protected function orBetween($column, $value = [])
    {
        return $this->between($column, $value, 'or');
    }

    /**
     * @param $column
     * @param string $operator
     * @param string $value
     * @param string $relation
     * @return string
     */
    protected function having($column, $operator = '', $value = '', $relation = 'and')
    {
        $conditionSql = 'having ' . $column;
        if ($value) {
            $name = $this->getSqlVariableName($column);
            $conditionSql . ' ' . $operator . ' ' . $name;
            $this->setValue($column, $value);
        }
        return $this->checkCondition($conditionSql, $relation);
    }

    /**
     * @param $column
     * @param string $operator
     * @param string $value
     * @return string
     */
    protected function orHaving($column, $operator = '', $value = '')
    {
        return $this->having($column, $operator, $value, 'or');
    }

    /**
     * @param $subSql
     * @param string $relation
     * @return string
     */
    protected function exist($subSql, $relation = 'and')
    {
        $conditionSql = 'exist ' . $subSql;
        return $this->checkCondition($conditionSql, $relation);
    }

    /**
     * @param $subSql
     * @return string
     */
    protected function orExist($subSql)
    {
        return $this->exist($subSql, 'or');
    }

    /**
     * @param $subSql
     * @param string $relation
     * @return string
     */
    protected function notExist($subSql, $relation = 'and')
    {
        $conditionSql = 'not exist ' . $subSql;
        return $this->checkCondition($conditionSql, $relation);
    }

    /**
     * @param $subSql
     * @return string
     */
    protected function orNotExist($subSql)
    {
        return $this->notExist($subSql, 'or');
    }

    /**
     * @param $conditionSql
     * @param $relation
     * @return string
     */
    protected function checkCondition($conditionSql, $relation)
    {
        if ($this->flag) {
            $this->flag = false;
            return $conditionSql . ' ';
        }
        return $relation . ' ' . $conditionSql . ' ';
    }
}