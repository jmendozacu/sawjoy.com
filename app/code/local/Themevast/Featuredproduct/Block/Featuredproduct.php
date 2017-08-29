<?php
class Themevast_Featuredproduct_Block_Featuredproduct extends Mage_Catalog_Block_Product_Abstract
{

	protected $_config = array();

	protected function _construct()
	{
		if(!$this->_config) $this->_config = Mage::getStoreConfig('featuredproduct/general'); 
	}

	public function getConfig($cfg = null)
	{
		if (isset($this->_config[$cfg]) ) return $this->_config[$cfg];
		return;
	}

	public function getColumnCount()
	{
		
		$slide = $this->getConfig('slide');
		$rows  = $this->getConfig('rows');
		if($slide && $rows >1) $column = $rows;
		else $column = $this->getConfig('qty');
		return $column;
	}

    protected function getProductCollection()
    {
		//edit by mivec
		$_cid = 151;
		$_category = Mage::getModel('catalog/category')->load($_cid);
		//print_r($_category->getData());exit;
		
    	$storeId = Mage::app()->getStore()->getId();
    	$attributes = Mage::getSingleton('catalog/config')->getProductAttributes();

/*        $collection = Mage::getModel('catalog/product')->getCollection()
				            	->addAttributeToSelect($attributes)
				              	->addMinimalPrice()
				              	->addFinalPrice()
				              	->addTaxPercents()
				              	->addAttributeToFilter('status', 1)
				              	->addAttributeToFilter('featured', 1)
				              	->addStoreFilter($storeId);*/
								
		$collection = Mage::getModel('catalog/product')->getCollection()
					->addCategoryFilter($_category)
					->addMinimalPrice()
					->addFinalPrice()
					->addTaxPercents()
					->addAttributeToFilter('status', 1)
					->addStoreFilter($storeId)
					->setOrder("entity_id" , 'DESC');
		//print_r($collection->getData());exit;
		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        
        $collection->setPageSize($this->getConfig('qty'))->setCurPage(1);
        Mage::getModel('review/review')->appendSummary($collection);     
        //var_dump($collection->getAllIds());                   
        return $collection;
    }

    public function setBxslider()
    {
  		$options = array(
  			'auto',
  			'speed',
  			'controls',
  			'pager',
  			'maxSlides',
  			'slideWidth',
  		);
  		$script = '';
  		foreach ($options as $opt) {
  			$cfg  =  $this->getConfig($opt);
  			$script    .= "$opt: $cfg, ";
  		}

  		$options2 = array(
  			'mode'=>'vertical',
  		);
  		foreach ($options2 as $key => $value) {
  			$cfg  =  $this->getConfig($value);
  			if($cfg) $script    .= "$key: '$value', ";
  		}

        return $script;

    }

}

