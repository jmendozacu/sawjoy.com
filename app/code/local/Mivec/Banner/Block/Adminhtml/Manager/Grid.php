<?php
class Mivec_Banner_Block_Adminhtml_Manager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('bannerGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		//$this->setUseAjax(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('banner/manager')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('id', array(
			'header'    => 'ID',
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'id',
		));
		
		$this->addColumn('title', array(
			'header'    => 'Title',
			'align'     =>'left',
			'index'     => 'title',
		));
		
		$this->addColumn('link', array(
			'header'    => 'Link',
			'align'     =>'left',
			'index'     => 'link',
		));
		
		$this->addColumn('image',array(
			'header'=> 'Image',
			'type' => 'image',
			'renderer'  => 'banner/adminhtml_renderer_grid_images',
			'width' => "100px",
			'index' => 'image',
		));
		
		$this->addColumn('position', array(
			'header'    => 'Position',
			'align'     => 'left',
			'width'     => '20px',
			'index'     => 'position',
			'type'      => 'options',
			'options'   => Mivec_Banner_Model_Position::getPosition()
		));
		
		$this->addColumn('description', array(
			'header'    => 'Description',
			'width'     => '100px',
			'index'     => 'description',
		));
		
		$this->addColumn('status', array(
			'header'    => 'Status',
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => Mivec_Banner_Model_Status::getStatus()
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
					),
					array(
						'caption'   => 'Delete',
						'url'       => array('base'=> '*/*/delete'),
						'field'     => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
		
		return parent::_prepareColumns();
	}
	
//	public function getRowUrl($row)	{
	  //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	//}
}