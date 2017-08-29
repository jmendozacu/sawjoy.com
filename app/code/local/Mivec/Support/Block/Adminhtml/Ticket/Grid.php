<?php
class Mivec_Support_Block_Adminhtml_Ticket_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function _construct()
    {
        parent::_construct();

        $this->setId("ticketGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("support/ticket")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _prepareColumns()
    {
        $this->addColumn("id" ,
            array(
                "header"    => "ID",
                "align"     => "left",
                "width"     => "20px",
                "index"     => "id"
            )
        );

        $_type = Mage::helper("support/type");
        $this->addColumn("type" ,
            array(
                "header"    => "Type",
                "align"     => "left",
                "width"     => "100px",
                "index"     => "type",
                "type"      => "options",
                "options"   => $_type->toOptions()
            )
        );

        $_deparment = Mage::helper("support/department");
        $this->addColumn("department" ,
            array(
                "header"    => "Department",
                "align"     => "left",
                "width"     => "50px",
                "index"     => "department",
                "type"      => "options",
                "options"   => $_deparment->toOptions()
            )
        );

        $_priority = Mivec_Support_Model_Ticket_Priority::getPriority();
        $this->addColumn("priority" ,
            array(
                "header"    => "Priority",
                "align"     => "left",
                "width"     => "30px",
                "index"     => "priority",
                "type"      => "options",
                "options"   => $_priority
            )
        );

        $_status = Mivec_Support_Model_Ticket_Status::getStatus();
        $this->addColumn("status" ,
            array(
                "header"    => "Status",
                "align"     => "left",
                "width"     => "30px",
                "index"     => "status",
                "type"      => "options",
                "options"   => $_status
            )
        );

        $this->addColumn("customer" ,
            array(
                "header"    => "Customer",
                "align"     => "left",
                "width"     => "50px",
                "renderer"  => "support/adminhtml_ticket_renderer_customer"
            )
        );

        $this->addColumn("subject" ,
            array(
                "header"    => "Subject",
                "align"     => "left",
                "width"     => "200px",
                "index"     => "subject",
            )
        );

        $this->addColumn("updated_at" ,
            array(
                "header"    => "Updated",
                "align"     => "left",
                "width"     => "30px",
                "type"      => "date",
                "index"     => "updated_at"
            )
        );

        $this->addColumn('action',
            array(
                'header'    =>  'Action',
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => 'Edit',
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                    ,array(
                        'caption'   => 'Close',
                        'url'       => array('base'=> '*/*/close'),
                        'field'     => 'id',
                        "confirm"   => "Could you want to close this ticket?"
                    )
                    ,array(
                        'caption'   => 'Delete',
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id',
                        'confirm'   => 'Are you sure that the record will be delete?'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    //public function getRowUrl($row)
   // {
        //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    //}
}