<?php
class Mivec_Ship_Model_Mysql4_Country extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init("ship/country" , "id");
	}
}