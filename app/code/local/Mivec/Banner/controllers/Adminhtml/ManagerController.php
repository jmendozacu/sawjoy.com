<?php
class Mivec_Banner_Adminhtml_ManagerController extends Mage_Adminhtml_Controller_Action
{
	protected function _init()
	{
		$this->loadLayout()
			->_setActiveMenu('mivec/banner')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Manager Banner'), Mage::helper('adminhtml')->__('Manager Banner'));
		
		return $this;
	}
	
	public function indexAction()
	{
		//$model = Mage::getModel('banner/manager')->getCollection();
		$this->_init()
			//->_addContent($this->getLayout()->createBlock('brandlogo/adminhtml_brandlogo'))
			->_addContent($this->getLayout()->createBlock('banner/adminhtml_manager'))
			->renderLayout();
	}
	
	public function editAction()
	{
	    $this->_init();
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('banner/manager')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('banner_data', $model);
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('banner/adminhtml_manager_edit'))
				->_addLeft($this->getLayout()->createBlock('banner/adminhtml_manager_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost()) {
			/**
			 * edit by mivec
			*/
			unset($data['image']);
			//print_r($_FILES);exit;
			if ($_FILES) {
				$_image = self::uploadFile('image');
				if (!empty($_image)) {
					$data['image'] = $_image;
				} /*else {
					$data['image'] = $data['image']['value'];
				}*/
			}
	  		if(isset($data['stores'])) $data['stores'] = implode(',', $data['stores']);
	  		//print_r($data);die;
			
			$model = Mage::getModel('banner/manager');
			
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime() == NULL || $model->getUpdatedTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdatedTime(now());
				} else {
					$model->setUpdatedTime(now());
				}
				
				//print_r($model);exit;
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brandlogo')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError('Unable to find item to save');
        $this->_redirect('*/*/');
	}
	
	public function newAction()
	{
		$this->_forward('edit');
	}
	
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('banner/manager');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
	
	protected function uploadFile($fileArea)
	{		
		if ($_FILES && !empty($_FILES[$fileArea]['name'])) {

			try {
				//Starting upload
				$uploader = new Varien_File_Uploader($fileArea);
				
				// Any extention would work
				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','zip','rar'));
				$uploader->setAllowRenameFiles(true);
				
				// Set the file upload mode 
				// false -> get the file directly in the specified folder
				// true -> get the file in the product like folders 
				//	(file.jpg will go in something like /media/f/i/file.jpg)
				$uploader->setFilesDispersion(false);
				
				$_mainDir = "themevast" . DS . "banner" . DS;
				// We set media as the upload dir
				$path = Mage::getBaseDir('media') . DS . $_mainDir;
				$uploader->save($path, $_FILES[$fileArea]['name']);
				$attachment = $_mainDir .$uploader->getUploadedFileName();
				return $attachment;
				
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}
	
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}