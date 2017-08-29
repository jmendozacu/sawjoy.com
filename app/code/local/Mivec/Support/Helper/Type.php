<?php
class Mivec_Support_Helper_Type extends Mage_Core_Helper_Abstract
{
    public function getCollection()
    {
        $collection = Mage::getModel("support/type")
            ->getCollection();
        return $collection;
    }

    public function getTypes($_status = "enabled")
    {
        $collection = $this->getCollection();
        if (!empty($_status)) {
            $collection->addAttributeToFilter("status" , $_status);
        }
        return $collection->getItems();
    }

    public function toOptions($_status = "enabled")
    {
        if ($_types = $this->getTypes($_status)) {
            $_data = array();
            foreach ($_types as $_item) {
                $_data[$_item->getId()] = $_item->getTitle();
            }
            return $_data;
        }
    }
}