<?php
class Mivec_Ship_Helper_Country extends Mage_Core_Helper_Abstract
{
	public function getCollection($_key="", $_value="")
	{
		$_collection = Mage::getModel('ship/country')
			->getCollection();
		if ($_key) {
			$_collection->addAttributeToFilter($_key , array("eq" => $_value));
		}
		return $_collection;
	}
	
	public function getCountries()
	{
		if ($_collection = $this->getCollection()) {
			$data = array();
			foreach ($_collection->getItems() as $_item) {
				$data[$_item->getId()] = $_item->getCountry();
			}
			return $data;
		}
	}
	
	public function getCountry($_key,$_value)
	{
		$_collection = $this->getCollection($_key , $_value);
		return $_collection->getData()[0];
	}
}