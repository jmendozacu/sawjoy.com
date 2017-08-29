<?php
class Mivec_Ship_Helper_Airmail extends Mage_Core_Helper_Abstract
{
	
	public function getShippingCollection()
	{
		$_quoteCollection = Mage::getModel("ship/airmail")
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