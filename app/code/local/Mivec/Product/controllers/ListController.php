<?php
class Mivec_Product_ListController extends Mage_Core_Controller_Front_Action
{
    public function newAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function topSellerAction()
    {
        $this->loadLayout()->renderLayout();
    }
}