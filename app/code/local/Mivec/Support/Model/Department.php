<?php
class Mivec_Support_Model_Department extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init("support/department");
    }
}