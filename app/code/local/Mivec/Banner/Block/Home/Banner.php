<?php
class Mivec_Banner_Block_Home_Banner extends Mage_Core_Template_Block
{
	public function getBanner($_position)
	{
		$banner = Mage::getModel('banner/manager')
			->getCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('position' , $_position);
		
		return $banner;
	}
}