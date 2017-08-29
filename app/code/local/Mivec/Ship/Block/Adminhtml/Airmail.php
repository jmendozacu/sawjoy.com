<?php
class Mivec_Ship_Block_Adminhtml_Airmail extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = "adminhtml_airmail";
		$this->_blockGroup = "ship";
		$this->_headerText = "Airmail Shipping Quotes";
		
		return parent::__construct();
	}
}