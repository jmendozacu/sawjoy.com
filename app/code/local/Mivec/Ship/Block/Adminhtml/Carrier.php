<?php
class Mivec_Ship_Block_Adminhtml_Carrier extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_carrier';
		$this->_blockGroup = 'ship';
		$this->_headerText = "Shipping Carrier List";
		parent::__construct();
	}
}