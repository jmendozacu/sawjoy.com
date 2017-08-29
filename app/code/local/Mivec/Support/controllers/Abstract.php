<?php
abstract class Mivec_Support_Controllers_Abstract extends Mage_Core_Controller_Front_Action
{
    protected $_session;

    protected function _init()
    {
        $this->_session = Mage::getSingleton("customer/session");
        return $this;
    }

    protected function ifCustomerLogin()
    {
        if (!$this->_session->getId()) {
            return false;
        }
        return true;
    }

    protected function redirectLogin($_forwardUrl="")
    {
      //  $_forwardUrl = Mage::getUrl('support/ticket/submit');
        $this->_session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
        $this->_redirect('customer/account/login' , array('uenc' => Mage::helper('core')->urlEncode($_forwardUrl)));
    }
}