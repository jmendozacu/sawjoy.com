<?php
class Mivec_Banner_Block_Adminhtml_Manager extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = "adminhtml_manager";
		$this->_blockGroup = "banner";
		$this->_headerText = "Banner Manager";
		$this->_addButtonLabel = "Add Item";
		parent::__construct();
	}
}