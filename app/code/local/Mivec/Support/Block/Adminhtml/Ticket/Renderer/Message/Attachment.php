<?php
class Mivec_Support_Block_Adminhtml_Ticket_Renderer_Message_Attachment extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $row->getAttachment();
    }
}