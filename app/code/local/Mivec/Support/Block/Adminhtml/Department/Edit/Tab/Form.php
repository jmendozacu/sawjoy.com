<?php
class Mivec_Support_Block_Adminhtml_Department_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("department_form" , array("legend"  => "Department"));

        $formData = Mage::registry("department_data")->getData();

        $fieldset->addField('name', 'text', array(
            'label'     => 'Department Name',
            'name'		=> 'name',
            'class'     => 'required-entry',
            'required'	=> true,
            'value'		=> $formData['name'],
            //'after_element_html' => 'Select Shipping Carrier',
        ));


        $_status = Mivec_Support_Model_Department_Status::getStatus();
        $fieldset->addField('status', 'select', array(
            'name'		=> 'status',
            'label'     => 'Status',
            'required'	=> true,
            'values'	=> $_status,
            'value'		=> $formData['status']
        ));

        if ( Mage::getSingleton('adminhtml/session')->getDepartmentData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getDepartmentData());
            Mage::getSingleton('adminhtml/session')->getDepartmentData(null);
        } elseif ( Mage::registry('department_data') ) {
            $form->setValues(Mage::registry('department_data')->getData());
        }
            
        return parent::_prepareForm();
    }
}