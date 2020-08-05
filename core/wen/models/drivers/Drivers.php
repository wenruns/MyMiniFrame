<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/22
 * Time: 11:30
 */

namespace core\wen\models\drivers;


use core\wen\models\Connect;
use core\wen\models\Query;

abstract class Drivers implements Driver
{
    /**
     * @var Query|null
     */
    protected $model = null;

    protected $values = [];

    /**
     * @var null|Connect
     */
    protected $connection = null;

    /**
     * Drivers constructor.
     * @param Query $model
     */
    public function __construct(Query $model)
    {
        $this->model = $model;
    }

    /**
     * @return string|void
     */
    public function toSql()
    {
        // TODO: Implement toSql() method.
        return $this->buildSql($this->model->getSqlInfos());
    }


    public function getConditions()
    {
        return $this->model->getConditions();
    }


    public function addValues($value)
    {
        $this->values[] = $value;
        return $this;
    }


    protected function setValue($value)
    {
        $this->model->addValues($value);
        return $this;
    }

    public function connect($configs)
    {
        $this->connection = new Connect($configs);
        return $this;
    }

    /**
     * @param $info
     * @return mixed
     */
    abstract protected function buildSql($info);


    abstract public function exec($sql);
}