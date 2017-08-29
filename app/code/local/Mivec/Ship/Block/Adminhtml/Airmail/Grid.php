<?php
class Mivec_Ship_Block_Adminhtml_Airmail_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('airmailGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('ship/airmail')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('id', array(
			'header'    => 'ID',
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'carrier_id',
		));
		//carrier
		$_carriers = Mage::helper('ship/carrier')->getCarriers(array('type') , array('airmail'));
		$this->addColumn('carrier_name', array(
			'header'    => 'Carrier',
			'align'     =>'left',
			'width'     => '200px',
			'type'		=> 'options',
			'index'		=> 'carrier_id',
			"options"	    => $_carriers,
		));
		
		//country
		$_countries = Mage::helper('ship/country')->getCountries();
		//print_r($_countries);exit;
		$this->addColumn('country', array(
			'header'    => 'Country Name',
			'align'     =>'left',
			'width'     => '100px',
			'type'		=> 'options',
			'index'		=> 'country_id',
			"options"	=> $_countries,
		));
		
		$this->addColumn('quote', array(
			'header'    => 'Quote Per Kilogram',
			'align'     =>'left',
			'width'     => '100px',
			'type'		=> 'number',
			'index'     => 'quote',
		));
		
		$this->addColumn('tracking_no', array(
			'header'    => 'Tracking Number Fee',
			'align'     =>'left',
			'width'     => '100px',
			'type'		=> 'number',
			'index'     => 'tracking_no',
		));
		
		$this->addColumn('updated_at' , array(
			'header'	=> 'Updated Date',
			'algin'		=> 'left',
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