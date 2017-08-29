<?php
class Mivec_Ship_Helper_Carrier extends Mage_Core_Helper_Abstract
{
	public function getCarrierCollection($_key="", $_value="")
	{
		$_collection = Mage::getModel('ship/carrier')
			->getCollection();
			
/*		if ($_key) {
			$_collection->addAttributeToFilter($_key , array("eq" => $_value));
		}*/
		if (is_array($_key)) {
			foreach ($_key as $i => $_field) {
				$_collection->addAttributeToFilter($_field , array("eq" => $_value[$i]));
			}
		}
		
		return $_collection;
	}
	
	public function getCarriers($_key = "", $_value = "")
	{
		if ($_collection = $this->getCarrierCollection($_key , $_value)) {
			$data = array();
			foreach ($_collection->getItems() as $_item) {
				//print_r($_item->getData());exit;
				$data[$_item->getCarrier_id()] = $_item->getCarrier_name();
			}
			return $data;
		}
	}
	
	public function getCarrier($_key,$_value)
	{
		$_collection = $this->getCarrierCollection($_key , $_value);
		return $_collection->getData()[0];
	}
}