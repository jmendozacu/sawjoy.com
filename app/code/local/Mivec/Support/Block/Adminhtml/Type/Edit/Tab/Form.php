<?php
class Mivec_Support_Block_Adminhtml_Type_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('type_form', array('legend' => "Ticket's Type"));

        $formData = Mage::registry("type_data")->getData();
        //print_r($formData);

        $fieldset->addField('title', 'text', array(
            'label'     => 'Title',
            'name'		=> 'title',
            'class'     => 'required-entry',
            'required'	=> true,
            'value'		=> $formData['title'],
            //'after_element_html' => 'Select Shipping Carrier',
        ));

        $_status = Mivec_Support_Model_Type_Status::getStatus();
        $fieldset->addField('status', 'select', array(
            'name'		=> 'status',
            'label'     => 'Status',
            'required'	=> true,
            'values'	=> $_status,
            'value'		=> $formData['status']
        ));

        if ( Mage::getSingleton('adminhtml/session')->getTypeData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getTypeData());
            Mage::getSingleton('adminhtml/session')->getTypeData(null);
        } elseif ( Mage::registry('type_data') ) {
            $form->setValues(Mage::registry('type_data')->getData());
        }
        return parent::_prepareForm();
    }
}