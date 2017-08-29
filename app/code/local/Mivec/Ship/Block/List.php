<?php
class Mivec_Ship_Block_List extends Mivec_Ship_Block_Abstract
{
	protected function _prepareLayout()
	{
		return parent::_prepareLayout();
	}
	
	public function getShippingQuoteList()
	{
		$collection = $this->helper("ship/quote")->getShippingCollection();
		
		if (isset($this->_params['carrier'])) {
			$collection->addAttributeToFilter('carrier_id' , $this->_params['carrier']);
		}
		
		if (isset($this->_params['country'])) {
			$collection->addAttributeToFilter('country_id' , $this->_params['country']);	
		}
		
		$collection->setOrder('id' , "DESC");
		//echo $collection->getSelect()->__toString();
		
		$toolbar = $this->getLayout()->createBlock('page/html_pager');
		//$toolbar = $this->getLayout()->createBlock('customer/order_list_toolbar');
		$toolbar->setCollection($collection);
		
		$quoteCollection = new stdClass;
		$quoteCollection->toolbar = $toolbar;
		$quoteCollection->items = '';
		
		if ($collection->count()) {
			$data = array();
			foreach ($collection->getItems() as $_quote) {
				$_carrierData = Mage::helper('ship/carrier')->getCarrier('carrier_id' , $_quote['carrier_id']);
				$_countryData = Mage::helper('ship/country')->getCountry('id' , $_quote['country_id']);
				//print_r($_countryData);exit;
				$data[] = array(
					'id'	=> $_quote['id'],
					'carrier_id'	=> $_quote['carrier_id'],
					'carrier'	=> $_carrierData['carrier_name'],
					'country_id'	=> $_quote['country_id'],
					'country'	=> $_countryData['country'],
					'quote'		=> "USD $" . ($_quote['quote_first'] + $_quote['quote_add']),
					'updated_at'	=> $_quote['updated_at']
				);
			}
			$quoteCollection->items = $data;
			return $quoteCollection;
		}
	}
	
	public function getCarrierOptions()
	{
		return $this->_carrierHelper->getCarriers();
	}
	
	public function getCountryOptions()
	{
		return $this->_countryHelper->getCountries();
	}
}