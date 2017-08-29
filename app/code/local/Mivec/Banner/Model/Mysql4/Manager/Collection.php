<?php
class Mivec_Banner_Model_Mysql4_Manager_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('banner/manager' , 'id');
    }
	
    public function addAttributeToFilter($key,$value,$method='and')
    {
        if (is_array($value)) {
            foreach ($value as $val) {
                $this->_select = $method == "and" ? $this->getSelect()->where($key . '=?' , $val) : $this->getSelect()->orwhere($key . '=?' , $val);
            }
        }else{
            $this->_select = $this->getSelect()->where($key . '=?' , $value);
        }
        return $this;
    }
	
/*
    public function fetch()
    {
        $sql = $this->_select->__toString();
        $this->_fetch = $this->getConnection()->fetchAll($sql);
        return $this;
    }

    public function getItems()
    {
        self::fetch();
        return $this->_fetch;
    }
	
	*/
}