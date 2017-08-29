<?php
class Mivec_Ship_Model_Mysql4_Express extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init("ship/express" , "id");
	}
}