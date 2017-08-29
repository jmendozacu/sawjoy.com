<?php

class Mivec_Banner_Block_Adminhtml_Manager_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('banner_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle('Item Information');
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => 'Item Information',
          'title'     => 'Item Information',
          'content'   => $this->getLayout()->createBlock('banner/adminhtml_manager_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}