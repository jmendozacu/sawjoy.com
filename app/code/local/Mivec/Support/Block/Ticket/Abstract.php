<?php
abstract class Mivec_Support_Block_Ticket_Abstract extends Mage_Core_Block_Template
{
    protected function _prepareLayout()
    {
        $this->_customerHelper = $this->helper("support/customer");
        $this->_session = $this->_customerHelper->getSession();

        $this->_departmentHelper = $this->helper("support/department");
        $this->_typeHelper = $this->helper("support/type");

        $this->_helper = $this->helper("support");

        return parent::_prepareLayout();
    }

    public function getHtmlType($_value = '')
    {
        $type = $this->_helper->formSelect('ticket[type]' , $this->_typeHelper->toOptions() , $_value);
        return $type;
    }

    public function getHtmlDepartment($_value = "")
    {
        $department = $this->_helper->formSelect("ticket[department]" , $this->_departmentHelper->toOptions() , $_value);
        return $department;
    }

    public function getHtmlPriority($_value = '')
    {
        $priority = $this->_helper->formSelect('ticket[priority]' , Mivec_Support_Model_Ticket_Priority::getPriority() , $_value);
        return $priority;
    }

    public function getHtmlStatus($_value = '')
    {
        $status = $this->_helper->formSelect('ticket[status]' , Mivec_Support_Model_Ticket_Status::getStatus() , $_value);
        return $status;
    }
}