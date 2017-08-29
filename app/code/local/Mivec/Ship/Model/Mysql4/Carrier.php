<?php
class Mivec_Ship_Model_Mysql4_Carrier extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init("ship/carrier" , "carrier_id");
	}
}