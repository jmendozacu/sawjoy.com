<?php
class Mivec_Ship_Block_Adminhtml_Carrier_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('carrier_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle('Carrier');
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
		  'label'     => "Carrier",
		  //'title'     => 'Carrier',
		  'content'   => $this->getLayout()->createBlock('ship/adminhtml_carrier_edit_tab_form')->toHtml(),
		));
		
/*		$this->addTab('transfer_section', array(
		  'label'     => Mage::helper('coupon')->__('Transfer'),
		  'title'     => Mage::helper('coupon')->__('Transfer'),
		  'content'   => $this->getLayout()->createBlock('coupon/adminhtml_coupon_edit_tab_transfer')->toHtml(),
		));*/
		return parent::_beforeToHtml();
	}
}