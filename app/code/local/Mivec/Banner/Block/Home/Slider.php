<?php
class Mivec_Banner_Block_Home_Slider extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
	{
		return parent::_prepareLayout();
	}
	
	public function getBannerSlider($_position)
	{
		if (!$_position) die("Need specific position");
		
		$banner = Mage::getModel('banner/manager')
			->getCollection()
			->addAttributeToFilter('position' , $_position)
			->addAttributeToFilter('status' , 1)
			->setPageSize(5);
		
		return $banner;
		//print_r($banner->getItems());exit;
	}
}
?>
