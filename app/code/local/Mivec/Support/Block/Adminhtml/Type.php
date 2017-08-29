<?php
class Mivec_Support_Block_Adminhtml_Type extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = "adminhtml_type";
        $this->_blockGroup = "support";
        $this->_headerText = "Type Of Ticket";

        return parent::__construct();
    }
}