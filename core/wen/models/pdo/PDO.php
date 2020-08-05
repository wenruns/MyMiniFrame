<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/4
 * Time: 13:33
 */

namespace core\wen\models\pdo;


class PDO
{
    /**
     * @var string
     */
    protected $_driver = 'mysql';

    /**
     * @var string
     */
    protected $_host = 'localhost';

    /**
     * @var string
     */
    protected $_port = '3306';

    /**
     * @var string
     */
    protected $_dbName = '';

    /**
     * @var string
     */
    protected $_username = 'root';

    /**
     * @var string
     */
    protected $_password = 'root';

    /**
     * @var array
     */
    protected $_options = [];

    /**
     * @var string
     */
    protected $_charset = 'utf8';

    /**
     * @var string
     */
    protected $_collate = 'utf8_general_ci';

    /**
     * @var null
     */
    protected $_unixSocket = null;

    /**
     * @var string
     */
    protected $_dns = '';

    /**
     * @var null|\PDO
     */
    protected $_pdo = null;

    /**
     * @return array
     */
    protected function getDnsAttr()
    {
        return [
            'host' => '_host',
            'dbname' => '_dbName',
            'port' => '_port',
            'charset' => '_charset',
        ];
    }


    public function __construct($configs = [])
    {
        foreach ($configs as $attr => $value) {
            if ($value) {
                $attr = '_' . $attr;
                $this->$attr = $value;
            }
        }
    }


    /**
     * @return string
     */
    public function getDns()
    {
        if (empty($this->_dns)) {
            $this->_dns = $this->_driver . ':';
            foreach ($this->getDnsAttr() as $name => $attr) {
                $this->_dns .= $name . '=' . $this->$attr . ';';
            }
        }
        return $this->_dns;
    }

    public function getOptions()
    {
        if (empty($this->_options)) {
            $this->_options = [
                \PDO::ATTR_PERSISTENT => true
            ];
        }
        return $this->_options;
    }

    public function connect()
    {
        try {
            $this->_pdo = new \PDO($this->getDns(), $this->_username, $this->_password, $this->getOptions());
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
        if (empty($this->_pdo)) {
            $this->connect();
        }
        return $this->_pdo;
    }


    public function beginTransaction()
    {
        $this->getPdo()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->getPdo()->beginTransaction();
        return $this;
    }

    public function commit()
    {
        $this->getPdo()->commit();
        return $this;
    }

    public function rollBack()
    {
        $this->getPdo()->rollBack();
        return $this;
    }


    public function transaction(\Closure $closure, $arguments = [])
    {
        $res = null;
        $this->beginTransaction();
        try {
            $res = call_user_func($closure, ...$arguments);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            throw new \Exception($e->getMessage());
        }
        return $res;
    }


}