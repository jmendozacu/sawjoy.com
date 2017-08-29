<?php
class Mivec_Support_Block_Adminhtml_Department_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('department_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle("Support Department");
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => "Department",
            'content'   => $this->getLayout()->createBlock('support/adminhtml_department_edit_tab_form')->toHtml(),
        ));

        /*		$this->addTab('transfer_section', array(
                  'label'     => Mage::helper('coupon')->__('Transfer'),
                  'title'     => Mage::helper('coupon')->__('Transfer'),
                  'content'   => $this->getLayout()->createBlock('coupon/adminhtml_coupon_edit_tab_transfer')->toHtml(),
                ));*/
        return parent::_beforeToHtml();
    }
}