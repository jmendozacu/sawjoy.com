<?php
require 'config.php';
$_method = $app->getRequest()->getParam('method');
$_categoryId = $app->getRequest()->getParam('category_id');

echo getCategory($_categoryId , $_method);


function getCategory($_categoryId,$_method = 'main',$_dir = 'DESC')
{
	$collection = Mage::getModel('catalog/category')
		->getCollection();
	
	$_key = 'entity_id';
	if ($_method != 'main') {
		$_key = 'parent_id';
	}
	$collection->addAttributeToFilter($_key, $_categoryId)
		->setOrder('entity_id' , $_dir);
	
	//echo $collection->getSelect()->__toString();
	
	$data = array();
	if ($collection->count() > 0) {
		foreach ($collection->getItems() as $_item) {
			$_category = Mage::getModel('catalog/category')->load($_item->getId());
			$data[] = array(
				'name'	=> $_category->getName(),
				'url'	=> $_category->getUrl()
			);
		}
		return json_encode($data);
	}
}