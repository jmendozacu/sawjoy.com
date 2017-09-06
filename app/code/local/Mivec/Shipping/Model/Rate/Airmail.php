<?php
class Mivec_Shipping_Model_Rate_Airmail extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'mivec_shippingar';
	protected $_quoteHelper;
    protected $_carrier;
	protected $_prefix = "carrier_";
	
    protected function _initSetup()
    {
		//helper
		$this->_quoteHelper = Mage::helper('ship/airmail');
		
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
		$_carriers = Mage::helper('ship/carrier')->getCarriers(array("type") , array("airmail"));
		if (count($_carriers) > 0) {
			foreach ($_carriers as $_id => $_carrier) {
				//check
				if ($this->_quoteHelper->checkQuotes("carrier_id" , $_id) > 0) {
					$title = strtoupper($_carrier . " Airmail");
					array_push($this->_carrier['id'] , $_id);
					array_push($this->_carrier['name'] , $this->_prefix . strtolower($_carrier));
					array_push($this->_carrier['title'] , $title);
				}
			}
		}
	}
	
    public function getAllowedMethods()
    {
		return array($this->_code => "mivec_shipping_airmail");
    }

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
		$this->_initSetup();
        $result = Mage::getModel('shipping/rate_result');
		//$result->append($this->_getAirmailRate($request));
		
		$i = 0;
		foreach ($this->_carrier['name'] as $_carrierCode) {
			if (!empty($_carrierCode)) {
				$result->append($this->_getAirmailRate($request,$this->_carrier['id'][$i] , $_carrierCode , $this->_carrier['title'][$i]));
				$i++;
			}
		}
        //$result->append($this->_getDhlRate($request));
        return $result;
    }

    protected function _getAirmailRate(Mage_Shipping_Model_Rate_Request $request , $_carrierId , $_carrierCode,$_carrierTitle)
    {
        $this->_initSetup();
        //echo $request->getPackageWeight();exit;

		if ($request->getPackageWeight() <= 2) {
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
		$_quoteCollection = $this->_quoteHelper->getShippingCollection()
			->addAttributeToFilter('carrier_id' , $_carrier['id'])
			->addAttributeToFilter('country_id' , $_countryData['id'])
            ->setOrder("id" , "DESC");

		$_quoteData = $_quoteCollection->getItems();

		//print_r($_quoteCollection->getSelect()->__toString());exit;
        if (is_array($_quoteData[0])) {
            $shippingPrice = 0;
            try {
                $shippingPrice = $this->calculatePrice(
                    $request, $_quoteData[0]
                );
                if (!empty($shippingPrice)) {
                    $shippingPrice = $shippingPrice / $_currencyRate;
                }

            } catch (Exception $e) {
                echo "Line:" . $e->getLine() ." ". $e->getMessage();
            }
            return $shippingPrice;
        }
    }
	
	protected function calculatePrice(Mage_Shipping_Model_Rate_Request $request , $_quote)
	{
		if (!empty($_quote['quote'])) {
			$_price = new stdClass;
			$_productWeight = $request->getPackageWeight();
			$_price->quote = ($_quote['quote'] * $_productWeight);
			//print_r($_price);exit;

			if ($_price->quote < 10) {
				$_price->quote = 10;
			}
			$_price->gt = $_price->quote + $_quote['tracking_no'];
			//echo $_productWeight;
			$_price->gt = round($_price->gt , 2);
			return $_price->gt;
		}
	}
}