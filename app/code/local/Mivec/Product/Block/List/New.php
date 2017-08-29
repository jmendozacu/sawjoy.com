<?php
class Mivec_Product_Block_List_New extends Mage_Core_Block_Template
{
    protected $_productCollection;
    protected $_category;
    const DEFAULT_SIZE = 100;

    protected function _prepareLayout()
    {
        $this->_params = $this->getRequest()->getParams();

        $web['title'] = $this->__("New Arrivial");
        //page title
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setTitle($web['title']);
        }
        //breadcrumbs
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home' , array(
                'label'	=> 'Home',
                'link'	=> Mage::getBaseUrl()
            ))
                ->addCrumb('new' , array(
                    'label'	=> $web['title'],
                    'title'	=> $web['title']
                ));
        }
        $this->setTemplate('mivec/product/list/new.phtml');
		

        return parent::_prepareLayout();

    }

    public function getProductCollection()
    {
		//time limit + 10
		$dayLimit = 10;
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
		$featureDate = date("Y-m-d" , strtotime($todayDate)- (86400*$dayLimit));
		//echo $featureDate;exit;
		
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status' , 1)
			->addAttributeToFilter('created_at', array('or'=> array(
				0 => array('date' => true, 'from' => $featureDate),
				1 => array('is' => new Zend_Db_Expr('null')))
			), 'left');
			//->addAttributeToFilter('created_at',array('gt'=>$featureDate));

        $collection->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left');
		
        $collection->getSelect()
			->order("inventory_in_stock DESC")
			->order("entity_id DESC")
			->limit(self::DEFAULT_SIZE);
			
        //echo $collection->getSelect()->__toString();
		
        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        //echo $productCollection->items->count();exit;

        return $collection;
    }

    public function setListOrders()
    {
        $category = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory();

        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $category->getAvailableSortByOptions();
        $this->getChild('product_new_list')
            ->setAvailableOrders($availableOrders);
    }

    public function setListCollection()
    {
        $this->getChild('product_new_list')
            ->setCollection($this->getProductCollection());
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('product_new_list');
    }

    public function getResultCount()
    {
        if (!$this->getData('result_count'))
        {
            $size = $this->getProductCollection()->getSize();
            $this->setResultCount($size);
        }
        return $this->getData('result_count');
    }
}