<?php
class Mivec_Support_TicketController extends Mivec_Support_Controllers_Abstract
{
    protected function _init()
    {
        parent::_init();

        $this->loadLayout();
        return $this;
    }

    public function postAction()
    {
        $this->_init();
        if (!$this->ifCustomerLogin()) {
            $_forwardUrl = Mage::getUrl("support/ticket/post");
            $this->redirectLogin();
        }

        $this->renderLayout();
    }

    public function listAction()
    {
        $this->_init()
            ->renderLayout();
    }
}