<?php
class Mivec_Ship_Model_Mysql4_Airmail extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init("ship/airmail" , "id");
	}
}