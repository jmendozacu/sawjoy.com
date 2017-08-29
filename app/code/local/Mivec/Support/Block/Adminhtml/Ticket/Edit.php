<?php
class Mivec_Support_Block_Adminhtml_Ticket_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function _construct()
    {
        parent::_construct();

        $this->_objectId = "id";
        $this->_blockGroup = "support";
        $this->_controller = "adminhtml_ticket";

        $this->_updateButton("save" , "label" , "Save");
        $this->_updateButton("delete" , "label" , "Delete");

        $this->_addButton("saveandcontinue" , array(
            "label"     => "Save And Continue Edit",
            "onclick"   => "saveAndContinueEdit()",
            "class"     => "save"
        ));

        $this->_formScripts[] = "
            function saveAndContinueEdit()
            {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry("ticket_data") && Mage::registry("ticket_data")->getId()) {
            return "Edit Ticket";
        } else {
            return "";
        }
    }
}