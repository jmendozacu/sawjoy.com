<?php
class Mivec_Shipping_Model_Rate_Express extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'mivec_shippingex';
	protected $_quoteHelper;
    protected $_carrier;
	protected $_prefix = "carrier_";
	
    protected function _initSetup()
    {
		//helper
		$this->_quoteExpress = Mage::helper('ship/express');
		
		//setup carrier data
		$this->_carrier['id'] = array();
		$this->_carrier['name'] = array();
		$this->_carrier['title'] = array();

		$this->setEnableCarrier();
        return $this;
    }

	protected function setEnableCarrier()
	{
		//get carriers
		$_carriers = Mage::helper('ship/carrier')->getCarriers("type" , "express");
		if (count($_carriers) > 0) {
			foreach ($_carriers as $_id => $_carrier) {
				//check express
				if ($this->_quoteExpress->checkQuotes("carrier_id" , $_id) > 0) {
					$title = strtoupper($_carrier . " Express");
					array_push($this->_carrier['id'] , $_id);
					array_push($this->_carrier['name'] , $this->_prefix . strtolower($_carrier));
					array_push($this->_carrier['title'] , $title);
				}
			}
		}
	}
	
    public function getAllowedMethods()
    {
		return array($this->_code => "mivec_shipping_express");
       //return $this->_methods;
    }

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
		$this->_initSetup();
        $result = Mage::getModel('shipping/rate_result');
		//$result->append($this->_getAirmailRate($request));
		
		$i = 0;
		foreach ($this->_carrier['name'] as $_carrierCode) {
			$result->append($this->_getShippingRate($request,$this->_carrier['id'][$i] , $_carrierCode , $this->_carrier['title'][$i]));
			$i++;
		}
		
        //$result->append($this->_getDhlRate($request));
        return $result;
    }

	protected function _getShippingRate(Mage_Shipping_Model_Rate_Request $request , $_carrierId , $_carrierCode,$_carrierTitle)
	{
		if ($request->getPackageWeight()) {
			$rate = Mage::getModel('shipping/rate_result_method');
	
			$rate->setCarrier($this->_code);
			$rate->setCarrierTitle($this->getConfigData('title'));
			$rate->setMethod($_carrierCode);
			$rate->setMethodTitle($_carrierTitle);
			
			//rebuild
			$_carrier = array(
				'id'	=> $_carrierId,
				'code'	=> $_carrierCode
			);
			$shippingPrice = $this->getShippingCost($request , $_carrier);
			if ($shippingPrice > 0) {
				$rate->setPrice($shippingPrice);
				return $rate;
			}
		}
	}
	
    protected function getShippingCost(Mage_Shipping_Model_Rate_Request $request , $_carrier)
    {
		$_countryHelper = Mage::helper('ship/country');
		//$_quoteHelper = Mage::helper('ship/quote');
		
        $request->setDestCountry(Mage::getModel('directory/country')->load($request->getDestCountryId())->getIso2Code());
		
		/*currency rate*/
		$allowedCurrencies = Mage::getModel('directory/currency')
                           ->getConfigAllowCurrencies();    
		$currencyRates = Mage::getModel('directory/currency')
                        ->getCurrencyRates('CNY', array_values($allowedCurrencies));
		$_currencyRate = (1 / $currencyRates['USD']);
		//echo $_currencyRate;
		
		//get country data
		$_countryData = $_countryHelper->getCountry('iso' , $request->getDestCountry());
		
		//get shipping quote
		$_quoteCollection = $this->_quoteExpress->getShippingCollection()
			->addAttributeToFilter('carrier_id' , $_carrier['id'])
			->addAttributeToFilter('country_id' , $_countryData['id'])
            ->setOrder("id" , "DESC");

		$_quoteData = $_quoteCollection->getItems();
		//print_r($_quoteData);exit;

		$shippingPrice = 0;
        try {
			$shippingPrice = $this->calculatePrice($request , $_quoteData[0]);
			$shippingPrice = $shippingPrice / $_currencyRate;
			
        } catch (Exception $e) {
            echo "Line:" . $e->getLine() ." ". $e->getMessage();
        }
        return $shippingPrice;
    }
		
	protected function calculatePrice(Mage_Shipping_Model_Rate_Request $request , $_quote)
	{
		$_price = new stdClass;
		
		$_productWeight = (int)$request->getPackageWeight() * 1000;
		//echo $_productWeight;
		
		$_weight['init'] = 500; //首重和续重的单位限制
		$_weight['added'] = $_productWeight - $_weight['init']; //续重

		/* 计算价格 */
		$_price->gt = 0;
		$_price->first = $_quote['quote_first'];	//首重价
		$_wei['added'] = "";
		if ($_productWeight > $_weight['init']) {
			//计算几个续重
			$_wei['added'] = ceil($_weight['added'] / $_weight['init']);
			if ($_wei['added'] < 1) {
				$_wei['added'] = 1;
			}
		}
		$_price->added =  $_wei['added'] * $_quote['quote_add']; //续重价
		$_totalPrice = $_price->fist + $_price->added;		
		//remote
		if (!empty($_quote)) {
			$_totalPrice += $_quote['remote'];
		}
		$_price->gt = round($_price->first + $_price->added , 2);
		return $_price->gt;
	}
}