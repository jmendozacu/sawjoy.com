<?php
class Mivec_Product_Block_List_Topseller extends Mage_Core_Block_Template
{
    protected $_productCollection;
    const CATEGORY_ID = 125;
    protected $_category;
    const DEFAULT_SIZE = 50;

    protected function _prepareLayout()
    {
        $this->_params = $this->getRequest()->getParams();

        $this->_category = Mage::getModel('catalog/category')->load(self::CATEGORY_ID);

        $web['title'] = $this->__("Top Seller");
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

        $this->setTemplate('mivec/product/list/topseller.phtml');
        return parent::_prepareLayout();

    }

    public function getProductCollection()
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addCategoryFilter($this->_category)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status' , 1);

        $collection->setOrder('entity_id' , 'DESC')
            ->setCurPage(1)
            ->setPageSize(self::DEFAULT_SIZE);

/*        $collection->getSelect()->limit(50)
            ->order('entity_id DESC');*/

        $collection->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
            ->setOrder('inventory_in_stock','desc');
        //echo $collection->getSelect()->__toString();

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $collection;
    }

    public function setListOrders()
    {
        $category = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory();

        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $category->getAvailableSortByOptions();
        $this->getChild('product_topseller_list')
            ->setAvailableOrders($availableOrders);
    }

    public function setListCollection()
    {
        $this->getChild('product_topseller_list')
            ->setCollection($this->getProductCollection());
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('product_topseller_list');
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