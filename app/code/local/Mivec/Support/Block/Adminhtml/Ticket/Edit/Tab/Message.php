<?php
class Mivec_Support_Block_Adminhtml_Ticket_Edit_Tab_Message extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId("ticketMsgGrid");
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        //$this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('support/message')
            ->getCollection()
            ->addAttributeToFilter('ticket_id' , $this->getRequest()->getParam('id'));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function _prepareColumns()
    {
        $this->addColumn('id' , array(
            'header'	=> 'ID',
            'type'      => 'number',
            'width'		=> '50px',
            'index'		=> 'id'
        ));

        $_role = Mivec_Support_Model_Message_Role::getRole();
        $this->addColumn('role' , array(
            'header'    => 'Role',
            'width'     => '100px',
            'index'     => 'role',
            "type"      => "options",
            "options"   => $_role
        ));

        $this->addColumn('content' , array(
            'header'    => 'Content',
            'index'     => 'content',
            'renderer'  => 'support/adminhtml_ticket_renderer_message_content',
        ));

        $this->addColumn('attachment' , array(
            'header'    => 'Attachment',
            'index'     => 'attachment',
            'renderer'  => 'support/adminhtml_ticket_renderer_message_attachment',
        ));

        $this->addColumn('created_at' , array(
            'header'    => 'Create Date',
            'width'     => '100px',
            'type'      => 'datetime',
            'index'     => 'created_at'
        ));

        $this->addColumn('action' , array(
            'header'	=>	'Action',
            'width'		=> '100',
            'type'		=> 'action',
            'getter'	=> 'getId',
            'actions'	=> array(
                array(
                    'caption'	=> 'Delete',
                    'field'		=> 'id',
                    'url'		=> array('base'=> '*/*/deletemsg'),
                    'confirm'   => 'Please make sure you will delete this data?'
                ),
            ),
            'filter'	=> false,
            'sortable'	=> false,
            'index'		=> 'stores',
            'is_system'	=> true,
        ));

        return parent::_prepareColumns();
    }
}