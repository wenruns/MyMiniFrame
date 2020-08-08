<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/4
 * Time: 13:33
 */

namespace core\wen\models\pdo;


use core\wen\models\SqlBuilder;

class PDO
{
    /**
     * @var string
     */
    protected $_dns = '';

    /**
     * @var null|\PDO
     */
    protected $_pdo = [];

    /**
     * @var null
     */
    protected $_active_pdo = null;

    /**
     * @var null
     */
    protected $_prepare = null;

    /**
     * @var array|mixed
     */
    protected $_configs = [];

    /**
     * @var SqlBuilder|null
     */
    protected $_sqlBuilder = null;


    /**
     * @return array
     */
    protected function getDnsAttr()
    {
        return [
            'host' => 'localhost',
            'dbName' => '',
            'port' => 3306,
            'charset' => 'utf8',
        ];
    }

    /**
     * @param $index
     * @param null $default
     * @return array|mixed|null
     */
    protected function getConfig($index, $default = null)
    {
        $configs = $this->_configs;
        $index = explode('.', $index);
        foreach ($index as $dex) {
            if (!isset($configs[$dex])) {
                return $default;
            }
            $configs = $configs[$dex];
        }
        return $configs;
    }

    /**
     * @return array|mixed|string|null
     */
    protected function getDefault()
    {
        $connect = $this->_sqlBuilder->getModel()->getConnection();
        if (empty($connect)) {
            $connect = $this->getConfig('default', 'mysql');
        }
        return $connect;
    }

    protected function initData()
    {
        $this->_sqlBuilder->initData();
        return $this;
    }

    /**
     * PDO constructor.
     * @param SqlBuilder $builder
     * @throws \Exception
     */
    public function __construct(SqlBuilder $builder)
    {
        $this->_sqlBuilder = $builder;
        $this->_configs = config('database', []);
    }


    /**
     * @return string
     */
    public function getDns()
    {
        if (empty($this->_dns)) {
            $this->_dns = $this->getConfig($this->getIndex('driver'), 'mysql') . ':';
            foreach ($this->getDnsAttr() as $name => $default) {
                $this->_dns .= strtolower($name) . '=' . $this->getConfig($this->getIndex($name), $default) . ';';
            }
        }
//        dd($this->_dns);
        return $this->_dns;
    }

    /**
     * @param $index
     * @return string
     */
    protected function getIndex($index)
    {
        return 'connections.' . $this->_active_pdo . '.' . $index;
    }

    /**
     * @return array|mixed|null
     */
    public function getOptions()
    {
        $options = $this->getConfig($this->getIndex('options'), []);
        if (empty($options)) {
            $options = [
                \PDO::ATTR_PERSISTENT => true
            ];
        }
        return $options;
    }

    /**
     * @return array|mixed|null
     */
    public function getUsername()
    {
        return $this->getConfig($this->getIndex('username'), 'root');
    }

    /**
     * @return array|mixed|null
     */
    public function getPassword()
    {
        return $this->getConfig($this->getIndex('password'), 'root');
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function connect()
    {
        try {
            $this->_pdo[$this->_active_pdo] = new \PDO($this->getDns(), $this->getUsername(), $this->getPassword(), $this->getOptions());
        } catch (\PDOException $e) {
            throw new \Exception('Connection failed: ' . $e->getMessage());
        }
        return $this;
    }

    /**
     * @return \PDO|null
     * @throws \Exception
     */
    public function getPdo()
    {
        $this->_active_pdo = $this->getDefault();
        if (!isset($this->_pdo[$this->_active_pdo]) || empty($this->_pdo[$this->_active_pdo])) {
            $this->connect();
        }
        return $this->_pdo[$this->_active_pdo];
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function beginTransaction()
    {
//        $this->getPdo()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->getPdo()->beginTransaction();
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function commit()
    {
        $this->getPdo()->commit();
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function rollBack()
    {
        $this->getPdo()->rollBack();
        return $this;
    }

    /**
     * @param \Closure $closure
     * @param array $arguments
     * @return mixed|null
     * @throws \Exception
     */
    public function transaction(\Closure $closure, $arguments = [])
    {
        $res = null;
        $this->beginTransaction();
        try {
            $res = call_user_func($closure, ...$arguments);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            dd($e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            $this->_sqlBuilder->getModel()->exception($e);
            return false;
        }
        return $res;
    }

    /**
     * @param null $fields
     * @return bool
     * @throws \Exception
     */
    public function get($fields = null)
    {
        $fields && $this->_sqlBuilder->getModel()->select($fields);
        $operator = 'select';
        $this->execute($operator);
        $res = $this->initData()->getPrepareHandle($operator)->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * @param $column
     * @param string $key
     * @return bool
     * @throws \Exception
     */
    public function pluck($column, $key = '')
    {
        if ($key) {
            $this->_sqlBuilder->getModel()->select([$key, $column]);
            $fetchType = \PDO::FETCH_KEY_PAIR;
        } else {
            $this->_sqlBuilder->getModel()->select($column);
            $fetchType = \PDO::FETCH_COLUMN;
        }
        $operator = 'select';
        $this->execute($operator);
        $res = $this->initData()->getPrepareHandle($operator)->fetchAll($fetchType);
        return $res;
    }

    /**
     * @param null $fields
     * @return bool
     * @throws \Exception
     */
    public function first($fields = null)
    {
        $fields && $this->_sqlBuilder->getModel()->select($fields);
        $this->_sqlBuilder->getModel()->limit(1)->offset(0);
        $operator = 'select';
        $this->execute($operator);
        $res = $this->initData()->getPrepareHandle($operator)->fetch(\PDO::FETCH_ASSOC);
        return $res;
    }


    /**
     * @param $operator
     * @return bool|\PDOStatement|null
     * @throws \Exception
     */
    protected function getPrepareHandle($operator)
    {
        if (isset($this->_prepare[$operator]) && $this->_prepare[$operator]) {
            return $this->_prepare[$operator];
        }
        $sql = $this->_sqlBuilder->buildSql($operator);
        $this->_prepare[$operator] = $this->getPdo()->prepare($sql);
        return $this->_prepare[$operator];
    }

    /**
     * @param $operator
     * @return bool
     * @throws \Exception
     */
    protected function execute($operator)
    {
        $prepareHandle = $this->getPrepareHandle($operator);
        foreach ($this->_sqlBuilder->getValues() as $key => $vo) {
            dump($operator . ':' . $key . '=' . $vo);
            $prepareHandle->bindValue($this->_sqlBuilder->getSqlVariableName($key), $vo);
        }
        $res = $prepareHandle->execute();
        if (!$res) {
            $error = $prepareHandle->errorInfo();
            throw new \Exception($error[1] . ':' . $error[2]);
        }
        return $res;
    }


    public function update($data)
    {

    }


    public function delete($primary = null)
    {

    }

    public function save($data = [], $conditionFields = [])
    {
        empty($data) && $data = $this->_sqlBuilder->getModel()->getOrigin();
        if (empty($data)) {
            throw new \Exception('Cannot save nothing.');
        }
        $primaryKey = $this->_sqlBuilder->getModel()->getPrimaryKey();
        if (count($data) == count($data, true)) {
            $this->_sqlBuilder->setValues($data);
            if (!empty($conditionFields) || isset($data[$primaryKey])) {
                $operator = 'update';
                foreach ($conditionFields as $column) {
                    $this->_sqlBuilder->getModel()->where($column, $data[$column]);
                }
                isset($data[$primaryKey]) && $this->_sqlBuilder->getModel()->where($primaryKey, $data[$primaryKey]);
            } else {
                $operator = 'insert';
            }
            $res = $this->execute($operator);
        } else {
            $res = $this->transaction(function ($data, $primaryKey, $conditionFields) {
                foreach ($data as $key => $item) {
                    $this->_sqlBuilder->setValues($item);
                    if (isset($item[$primaryKey]) || !empty($conditionFields)) {
                        $operator = 'update';
                        if (!isset($this->_prepare[$operator])) {
                            foreach ($conditionFields as $column) {
                                $this->_sqlBuilder->getModel()->where($column, $item[$column]);
                            }
                            isset($item[$primaryKey]) && $this->_sqlBuilder->getModel()->where($primaryKey, $item[$primaryKey]);
                        }
                    } else {
                        $operator = 'insert';
                    }
                    $this->execute($operator);
                }
            }, [$data, $primaryKey, $conditionFields]);
        }
        $this->initData();
        return $res;
    }


}