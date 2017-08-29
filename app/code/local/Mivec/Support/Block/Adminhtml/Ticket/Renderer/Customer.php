<?php
//class Mivec_Support_Block_Adminhtml_Ticket_Edit_Renderer_Customer extends Mage_Adminhtml_Block_Abstractimplements Varien_Data_Form_Element_Renderer_Interface
class Mivec_Support_Block_Adminhtml_Ticket_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        //customer_id
        $_customer = Mage::getModel("customer/customer")->load($row->getCustomerId());
        $html = "";
        if ($_customer->getId()) {
            $html .= '<a title="view profile" href="'.$this->getUrl('adminhtml/customer/edit', array('id' => $_customer->getId())).'">'.$_customer->getEmail().'</a>';
        }
        return $html;
    }
}