<?php
class Mivec_Ship_Helper_Express extends Mage_Core_Helper_Abstract
{
	
	public function getShippingCollection()
	{
		$_quoteCollection = Mage::getModel("ship/express")
			->getCollection();
		return $_quoteCollection;
	}
	
	public function checkQuotes($_key , $_value)
	{
		$_quoteCollection = $this->getShippingCollection()
			->addAttributeToFilter($_key , $_value);
		return $_quoteCollection->getCount();
	}
}