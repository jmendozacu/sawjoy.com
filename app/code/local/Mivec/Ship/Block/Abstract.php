<?php
abstract class Mivec_Ship_Block_Abstract extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
	{
		$this->_params = $this->getRequest()->getParams();
        $this->_carrierHelper = $this->helper('ship/carrier');
		$this->_countryHelper = $this->helper('ship/country');
		
		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle('Shipping Quotes');
		}
		
		if ($breadCrumb = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadCrumb->addCrumb('home' , array(
				'label'	=> 'Home',
				'link'	=> Mage::getBaseUrl()
			))
			->addCrumb('ship_quote' , array(
				'label'	=> 'Shipping Quotes',
				'link'	=> Mage::getUrl("ship/quote")
			));
		}
		
		return parent::_prepareLayout();
	}
}