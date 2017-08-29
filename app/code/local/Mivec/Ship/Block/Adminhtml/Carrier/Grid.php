<?php
class Mivec_Ship_Block_Adminhtml_Carrier_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('carrierGrid');
		$this->setDefaultSort('carrier_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('ship/carrier')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('carrier_id', array(
			'header'    => 'ID',
			'align'     =>'right',
			'width'     => '100px',
			'index'     => 'carrier_id',
		));
		
		$this->addColumn('name', array(
			'header'    => 'Carrier Name',
			'align'     =>'left',
			'width'     => '300px',
			'index'     => 'carrier_name',
		));
		
		$this->addColumn('type' , array(
			'header'	=> 'Type',
			'align'		=> 'left',
			'index'		=> 'type',
			'type'		=> 'options',
			"options"	=> Mivec_Ship_Model_Carrier_Type::getCarrierType(),
		));
		
		$this->addColumn('updated_at' , array(
			'header'	=> 'Updated Date',
			'align'		=> 'left',
			'type'		=> 'date',
			'index'		=> 'updated_at'
		));
		
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
						'confirm'   => 'Are you sure?'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
		return parent::_prepareColumns();
	}
	
	public function getRowUrl($row)
	{
		//return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}