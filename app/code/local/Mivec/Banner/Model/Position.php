<?php
class Mivec_Banner_Model_Position extends Mage_Core_Model_Abstract
{
	const POSITION = array(
		1		=> "Home_Banner_1",
		2		=> "Home_Banner_2",
		3		=> 'Home_Product_Slider_1',
		4		=> 'Home_Product_Slider_2',
		5		=> 'Catalog List',
		6		=> 'Product View'
	);
	
	public static function getPosition()
	{
		return self::POSITION;
	}
	
	public static function getPositionValues()
	{
		$result = array();
		foreach (self::POSITION as $_key => $_val) {
			$result[] = array(
				'value'	=> $_key,
				'label'	=> $_val
			);
		}
		return $result;
	}
}