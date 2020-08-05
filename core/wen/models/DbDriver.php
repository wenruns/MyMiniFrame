<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:42
 */

namespace core\wen\models;


use core\wen\models\drivers\Drivers;
use core\wen\models\drivers\Mysql;

class DbDriver
{
    protected $builder = null;

    protected $configs = [];

    protected $driver = null;

    /**
     * @return Drivers|null
     * @throws \Exception
     */
    protected function driver()
    {
        if (empty($this->driver)) {
            $driver = $this->getConfig('driver', 'mysql');
            switch ($driver) {
                case 'mysql':
                default:
                    $this->driver = new Mysql($this->builder->getModel());
            }
        }
        return $this->driver;
    }

    /**
     * @return string
     */
    protected function getConnection()
    {
        return $this->builder->getModel()->getConnection();
    }

    /**
     * @return string
     */
    protected function getConfigIndex()
    {
        return 'database.connections.' . $this->getConnection();
    }

    /**
     * @param string $index
     * @param null $default
     * @return array|mixed|null
     * @throws \Exception
     */
    protected function getConfig($index = '', $default = null)
    {
        if (empty($this->configs)) {
            $this->configs = config($this->getConfigIndex(), []);
        }
        return $index ? (isset($this->configs[$index]) ? $this->configs[$index] : $default) : $this->configs;
    }

    /**
     * DbDriver constructor.
     * @param SqlBuilder $builder
     */
    public function __construct(SqlBuilder $builder)
    {
        $this->builder = $builder;
    }


    /**
     * @param $column
     * @param $key
     */
    public function pluck($column, $key)
    {

    }

    /**
     *
     */
    public function toSql()
    {
        return $this->driver()->toSql();
    }

    public function addValues($value)
    {
        $this->driver()->addValues($value);
        return $this;
    }


    public function exec($sql)
    {
        return $this->driver()->connect($this->getConfig())->exec($sql);
    }
}