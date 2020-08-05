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
    protected $_query = null;

    protected $_driver = null;

    /**
     * @return DbDriver|null
     */
    public function getDriver()
    {
        if (empty($this->_driver)) {
            $this->_driver = new DbDriver($this);
        }
        return $this->_driver;
    }


    /**
     * SqlBuilder constructor.
     * @param $query
     */
    public function __construct(Query $query)
    {
        $this->_query = $query;
    }

    /**
     * @return Query|Model|null
     */
    public function getModel()
    {
        return $this->_query;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->getModel()->getFields();
    }


    public function exec($sql)
    {
        return $this->getDriver()->exec($sql);
    }

    /**
     * @return string
     */
    public function toSql()
    {
        return $this->getDriver()->toSql();
    }
}