<?php
class Mivec_Support_Block_Adminhtml_Department_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function _construct()
    {
        parent::_construct();

        $this->setId("departmentGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("support/department")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _prepareColumns()
    {
        $this->addColumn("id" ,
            array(
                "header"    => "ID",
                "align"     => "left",
                "width"     => "50px",
                "index"     => "id"
            )
        );

        $this->addColumn("name" ,
            array(
                "header"    => "Department's Name",
                "align"     => "left",
                "width"     => "250px",
                "index"     => "name"
            )
        );

        $_status = Mivec_Support_Model_Department_Status::getStatus();
        $this->addColumn("status" ,
            array(
                "header"    => "Status",
                "align"     => "left",
                "index"     => "status",
                'width'     => "150px",
                "type"      => "options",
                "options"   => $_status
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

    public function getRowUrl($row)
    {
        //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}