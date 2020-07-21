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
    public function __construct($query)
    {
        $this->_query = $query;
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        if ($this->_query instanceof Model) {
            return $this->_query;
        }
        return null;
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

    }

    /**
     * @return string
     */
    public function toSql()
    {
        return $this->getDriver()->toSql();
    }
}