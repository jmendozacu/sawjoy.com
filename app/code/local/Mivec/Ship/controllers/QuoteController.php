<?php
class Mivec_Ship_QuoteController extends Mage_Core_Controller_Front_Action
{
	protected function _init()
	{
		return $this;
	}
	
    public function indexAction()
    {
        self::_init();
        $this->loadLayout()
            ->renderLayout();
    }
}