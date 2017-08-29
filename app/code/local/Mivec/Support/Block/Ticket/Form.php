<?php
class Mivec_Support_Block_Ticket_Form extends Mivec_Support_Block_Ticket_Abstract
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setTitle('Open New Ticket');
        }

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home' , array(
                    'label'	=> 'Home',
                    'link'	=> Mage::getBaseUrl(),
                    'readonly'	=> true
                ))
                ->addCrumb("ticket" , array(
                    "label" => "Open New Ticket",
                    "link"  => Mage::getUrl("support/ticket/post"),
                ));
        }

    }
}