<?php
class Mivec_Banner_Model_Manager extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('banner/manager');
    }
}