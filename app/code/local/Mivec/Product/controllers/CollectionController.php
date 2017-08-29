<?php
class Mivec_Product_CollectionController extends Mage_Core_Controller_Front_Action
{
    //new arrival
    public function latestAction()
    {
        $this->loadLayout()->renderLayout;
    }

    public function topsellerAction()
    {
        $this->loadLayout()->renderLayout;
    }
}