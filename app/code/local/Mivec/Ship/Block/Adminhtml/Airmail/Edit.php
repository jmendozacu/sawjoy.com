<?php
class Mivec_Ship_Block_Adminhtml_Airmail_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function _construct()
	{
        parent::_construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'ship';
        $this->_controller = 'adminhtml_airmail';
        
        $this->_updateButton('save', 'label', 'Save');
        $this->_updateButton('delete', 'label', 'Delete');
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
	}
	
    public function getHeaderText()
    {
        if( Mage::registry('airmail_data') && Mage::registry('airmail_data')->getId() ) {
            return Mage::helper('ship')->__("Edit Airmail Shipping Quote '%s'" , "");
        } else {
            return "";
        }
    }
}