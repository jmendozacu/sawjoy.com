<?php
class Mivec_Support_Block_Adminhtml_Department extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = "adminhtml_department";
        $this->_blockGroup = "support";
        $this->_headerText = "Support Department";

        return parent::__construct();
    }
}