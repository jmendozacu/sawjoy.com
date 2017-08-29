<?php
class Mivec_Ship_Block_Home_Quote extends Mage_Core_Block_Template
{
/*	protected function _preparelayout()
	{
		return parent::_prepareLayout();
	}*/
	
	public function getShippingQuote()
	{
		$_collection = Mage::helper('ship/quote')->getShippingCollection()
			//->addAttributeToFilter()
			->setPageSize(10)
			->setOrder('id' , 'DESC');
		return $_collection;
	}
}