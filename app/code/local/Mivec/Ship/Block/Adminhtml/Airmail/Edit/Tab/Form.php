<?php
class Mivec_Ship_Block_Adminhtml_Airmail_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('airmail_form', array('legend' => 'Airmail Shipping Quote'));
		
		$formData = Mage::registry("airmail_data")->getData();
		//print_r($formData);
		
		$_carriers = Mage::helper('ship/carrier')->getCarriers(array('type') , array('airmail'));
		$fieldset->addField('carrier_id', 'select', array(
			'label'     => 'Carrier',
			'name'		=> 'carrier_id',
			'class'     => 'required-entry',
			'required'	=> true,
			'values'	=> $_carriers,
			'value'		=> $formData['carrier_id'],
			//'after_element_html' => 'Select Shipping Carrier',
		));

		$_countries = Mage::helper('ship/country')->getCountries();
		$fieldset->addField('country_id', 'select', array(
			'name'		=> 'country_id',
			'label'     => 'Country',
			'required'	=> true,
			'values'	=> $_countries,
			'value'		=> $formData['country_id']
		));
		
		$fieldset->addField('quote' , "text" , array(
			'name'		=> 'quote',
			'label'		=> 'Quote Per Kilogram',
			'required'     => true,
			'value'		=> $formData['quote'],
		));
		
		$fieldset->addField('tracking_no' , "text" , array(
			'name'		=> 'tracking_no',
			'label'		=> 'Tracking Number Fee',
			'required'     => true,
			'value'		=> $formData['tracking_no'],
		));
		
        if ( Mage::getSingleton('adminhtml/session')->getAirmailData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getAirmailData());
			Mage::getSingleton('adminhtml/session')->getAirmailData(null);
		} elseif ( Mage::registry('airmail_data') ) {
			$form->setValues(Mage::registry('airmail_data')->getData());
		}
			return parent::_prepareForm();
		}
}