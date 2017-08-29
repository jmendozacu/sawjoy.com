<?php
class Mivec_Support_Helper_Department extends Mage_Core_Helper_Abstract
{
    public function getCollection()
    {
        $collection = Mage::getModel("support/department")
            ->getCollection();
        return $collection;
    }

    public function getDepartments($_status = "enabled")
    {
        $collection = $this->getCollection();
        if (!empty($_status)) {
            $collection->addAttributeToFilter("status" , $_status);
        }
        return $collection->getItems();
    }

    public function toOptions($_status = "enabled")
    {
        if ($_departments = $this->getDepartments($_status)) {
            $_data = array();
            foreach ($_departments as $_item) {
                $_data[$_item->getId()] = $_item->getName();
            }
            return $_data;
        }
    }
}