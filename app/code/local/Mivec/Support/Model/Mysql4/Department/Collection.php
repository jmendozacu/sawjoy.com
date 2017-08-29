<?php
class Mivec_Support_Model_Mysql4_Department_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init("support/department");
    }

    public function addAttributeToFilter($key , $value , $method = 'and')
    {
        switch($method) {
            case "in":
                if (is_array($value)) {
                    $_str = "";
                    foreach ($value as $val) {
                        $_str .= $val . ",";
                    }
                    $_str = trim($_str , ',');
                    $this->getSelect()->where($key . " IN($_str)");
                }
                break;

            case "like" :
                $this->getSelect()->orWhere($key . " LIKE ?", "%" .$value . "%");
                break;

            default:
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $this->_select = $method == "and" ? $this->getSelect()->where($key . '=?' , $val) : $this->getSelect()->orwhere($key . '=?' , $val);
                    }
                }else{
                    $this->_select = $this->getSelect()->where($key . '=?' , $value);
                }
        }
        return $this;
    }

    /*
    public function getCount()
    {
        $sql = $this->_select->__toString();
        $count = $this->getConnection()->fetchOne($sql);
        return $count;
    }

    public function fetch()
    {
        $sql = $this->_select->__toString();
        //echo $sql;exit;

        $this->_fetch = $this->getConnection()->fetchAll($sql);
        return $this;
    }

    public function getItems()
    {
        self::fetch();
        return $this->_fetch;
    }

    public function setOrder($key , $dir = 'DESC')
    {
        $this->getSelect()->order("{$key} $dir");
        return $this;
    }
    */
}