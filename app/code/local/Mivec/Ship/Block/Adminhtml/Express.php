<?php
class Mivec_Ship_Block_Adminhtml_Express extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = "adminhtml_express";
		$this->_blockGroup = "ship";
		$this->_headerText = "Express Shipping Quotes";
		
		return parent::__construct();
	}
}