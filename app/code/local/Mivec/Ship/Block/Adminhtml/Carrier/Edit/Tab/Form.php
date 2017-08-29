<?php
class Mivec_Ship_Block_Adminhtml_Carrier_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('carrier_form', array('legend' => 'Carrier'));
		
		//print_r(Mage::registry('carrier_data')->getData());exit;
		
		$fieldset->addField('carrier_name', 'text', array(
			'label'     => 'Carrier Name',
			'name'      => 'carrier_name',
			'required'	=> true,
			'value'		=> Mage::registry('carrier_data')->getData("carrier_name")
		));
		
		$fieldset->addField('type', 'select', array(
			'label'     => "Type",
			'name'      => 'type',
			'required'	=> true,
			'value'		=> Mage::registry('carrier_data')->getData('type'),
			'values'    => Mivec_Ship_Model_Carrier_Type::getCarrierType()
		));
		 
/*		//assignment customer service
		if ($staffs = Mage::getModel('dropship/staff')) {
			$collection = $staffs->getCollection();
			$i=0;
			foreach ($collection->getItems() as $item) {
				$staff_id = $item->getId();
				$staff_name = $item->getName();
				$arr[] = array(
					'value'	=> $staff_id,
					'label'	=> $staff_name,
				);
				$i++;
			}
			$fieldset->addField('services', 'select', array(
				'label'     => Mage::helper('coupon')->__('Customer Service'),
				'name'      => 'services',
				'values'    => $arr,
			));
		}
		
		$fieldset->addField('remark', 'textarea', array(
			'label'     => Mage::helper('coupon')->__('remark'),
			'name'      => 'drop[remark]',
			'value'	=> Mage::registry('dropship_data')->getRemark()
		));*/
		
	/*		$fieldset->addField('store', 'text', array(
			  'label'     => Mage::helper('coupon')->__('store url'),
			  'class'     => 'required-entry',
			  'required'  => true,
			  'name'      => 'store',
			  'value'	=> Mage::registry('dropship_data')->getStore()
			));
			
	*/		
		 /* $fieldset->addField('content', 'editor', array(
			  'name'      => 'content',
			  'label'     => Mage::helper('producttags')->__('Content'),
			  'title'     => Mage::helper('producttags')->__('Content'),
			  'style'     => 'width:700px; height:500px;',
			  'wysiwyg'   => false,
			  'required'  => true,
		  ));*/
		 
		if ( Mage::getSingleton('adminhtml/session')->getCarrierData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getCarrierData());
			Mage::getSingleton('adminhtml/session')->getCarrierData(null);
		} elseif ( Mage::registry('carrier_data') ) {
			$form->setValues(Mage::registry('carrier_data')->getData());
		}
			return parent::_prepareForm();
		}
}