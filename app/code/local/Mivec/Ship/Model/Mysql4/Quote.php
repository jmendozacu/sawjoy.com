<?php
class Mivec_Ship_Model_Mysql4_Quote extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init("ship/quote" , "id");
	}
}