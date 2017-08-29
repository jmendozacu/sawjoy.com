<?php
class Mivec_Support_Block_Adminhtml_Ticket_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('ticket_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Ticket');
    }

    public function _beforeToHtml()
    {
        $this->addTab('form_section' , array(
            'label'     => 'Ticket',
            'title'     => 'Ticket Detail',
            'content'   => $this->getLayout()->createBlock('support/adminhtml_ticket_edit_tab_form')->toHtml(),
            'active'    => true,
        ));

        $this->addTab('message_section' , array(
            'label'     => 'Message',
            'title'     => 'Ticket Message',
            'content'   => $this->getLayout()->createBlock('support/adminhtml_ticket_edit_tab_message')->toHtml(),
            'active'    => true,
        ));

        return parent::_beforeToHtml();
    }
}