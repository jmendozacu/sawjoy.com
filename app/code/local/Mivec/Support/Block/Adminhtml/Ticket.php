<?php
class Mivec_Support_Block_Adminhtml_Ticket extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = "adminhtml_ticket";
        $this->_blockGroup = "support";
        $this->_headerText = "Ticket";

        return parent::__construct();
    }
}