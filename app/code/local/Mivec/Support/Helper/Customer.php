<?php
class Mivec_Support_Helper_Customer extends Mage_Core_Helper_Abstract
{
    protected $_session;

    protected function _init()
    {
        $this->_session = Mage::getSingleton("customer/session");
        return $this;
    }

    public function getSession()
    {
        $this->_init();
        return $this->_session;
    }

    public function getCustomer($_CustomerId)
    {
        $customer = Mage::getModel('customer/customer')
            ->load($_CustomerId);
        return $customer;
    }
}