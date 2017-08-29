<?php
class Mivec_Banner_Block_Adminhtml_Manager_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('banner_form', array('legend'=>'Item information'));
		
      $fieldset->addField('title', 'text', array(
          'label'     => 'Title',
          'required'  => false,
          'name'      => 'title',
      ));
	  
  	  $fieldset->addField('link', 'text', array(
            'label'     => 'Link',
            'required'  => false,
            'name'      => 'link',
        ));

        $fieldset->addField('image', 'image', array(
            'label'     => 'Image',
            'required'  => true,
            'name'      => 'image',
  	  ));

      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => 'Description',
          'title'     => 'Description',
          'style'     => 'width:275px; height:200px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));

      //if (!Mage::app()->isSingleStoreMode()) {
          $fieldset->addField('stores', 'multiselect', array(
              'name' => 'stores[]',
              'label' => $this->__('Store View'),
              'required' => TRUE,
              'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
          ));
      //}
	  
	  //print_r(Mivec_Banner_Model_Position::getPositionValues());exit;
	  
	  $fieldset->addField('position' , 'select' , array(
	  		'label'		=> 'position',
			'name'		=> 'position',
			'values'	=> Mivec_Banner_Model_Position::getPositionValues()
	  ));
	  
      $fieldset->addField('status', 'select', array(
          'label'     => 'Status',
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => 'Enabled',
              ),

              array(
                  'value'     => 0,
                  'label'     => 'Disabled',
              ),
          ),
      ));

     
      if ( Mage::getSingleton('adminhtml/session')->getBannerData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBannerData());
          Mage::getSingleton('adminhtml/session')->getBannerData(null);
      } elseif ( Mage::registry('banner_data') ) {
          $form->setValues(Mage::registry('banner_data')->getData());
      }
      return parent::_prepareForm();
  }
}

