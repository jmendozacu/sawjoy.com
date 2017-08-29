<?php
class Mivec_Ship_Model_Carrier_Type extends Mage_Core_Model_Abstract
{
	static $_TYPE = array(
		'express'	=> 'Express',
		'airmail'	=> 'Airmail'
	);
	
	public static function getCarrierType()
	{
		return self::$_TYPE;
	}
}