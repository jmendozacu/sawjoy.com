<?php
class Mivec_Banner_Model_Mysql4_Manager extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('banner/manager' , "id");
    }
}