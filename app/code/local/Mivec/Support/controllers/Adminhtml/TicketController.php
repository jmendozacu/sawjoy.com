<?php
class Mivec_Support_Adminhtml_TicketController extends Mage_Adminhtml_Controller_Action
{
    protected function _init()
    {
        $this->loadLayout()
            ->_setActiveMenu("mivec/support")
            ->_addBreadcrumb("Ticket list" , "Ticket List");
        return $this;
    }

    public function indexAction()
    {
        $this->_init()
            ->renderLayout();
    }

    public function editAction()
    {
        $this->_init();

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('support/ticket')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($model)) {
                $model->setData($data);
            }

            Mage::register('ticket_data' , $model);
            //$this->_init();
            $this->loadLayout();
            $this->_setActiveMenu('mivec/support');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('support/adminhtml_ticket_edit'));
            $this->_addLeft($this->getLayout()->createBlock('support/adminhtml_ticket_edit_tabs'));

            $this->renderLayout();

        }else {
            Mage::getSingleton('adminhtml/session')->addError('Ticket does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function saveAction($e)
    {
        if ($data = $this->getRequest()->getPost()){
            //print_r($data);exit;
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('support/ticket');
            $message = Mage::getModel('support/message');

            $d = $data['ticket'];
            $content = $d['content'];
            $_messageStatus = false;
            if (strlen($content) > 2) {
                $sData['status'] = 3; //wait customer reply //change status
                try {
                    //增加回复记录
                    $rData['ticket_id'] = $id;
                    $rData['role'] = 'supporter';
                    $rData['content'] = $content;
                    $rData['created_at'] = date('Y-m-d H:i:s');
                    $message->setData($rData)
                        ->save();
                    $_messageStatus = true;

                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $id));
                    return;
                }
            }else{
                Mage::getSingleton('adminhtml/session')->addError('post message can not less than 2 words');
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
            $sData['type'] = $d['type'];
            $sData['priority'] = $d['priority'];
            $sData['updated_at'] = date('Y-m-d H:i:s');

            //save ticket
            $model->setData($sData)
                ->setId($id);

            if ($model->save() && $_messageStatus) {
               /* $_url = Mage::getUrl('support/ticket/view' , array('id' => $id));
                //sendmail
                $mail = array();
                $mail['ticket_id'] = $id;
                $mail['email'] = $data['email'];
                $mail['url'] = "<a href='$_url'>" .$_url . "</a>";

                if ($model->sendMail($mail , 'update')) {
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('support')->__('It was successfully saved'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    if ($this->getRequest()->getParam('back')) {
                        //$this->_redirect('/edit', array('id' => $model->getId()));
                        return;
                    }
                    $this->_redirect('index');
                    return;
                }*/
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/index');
            }else{
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
    }

    public function closeAction()
    {
        if ($id = $this->getRequest()->getParam("id")) {
            $model = Mage::getModel("support/ticket");
            $data = array(
                "status"    => 2
            );
            $model->setData($data)
                ->setId($id);

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess('It was successfully Closed');
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
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $model = Mage::getModel('support/ticket')->load($id);
            try {
                if ($model->delete()) {
                    Mage::getSingleton('adminhtml/session')->addSuccess('Delete Ticket Successfully');
                }
                $this->_redirect('*/*/index');
                return ;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    public function deletemsgAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $model = Mage::getModel('support/message');
            try{
                $model->load($id);
                $_SESSION['ticket_id'] = $model->getTicketId();
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess('Delete Message Successfully');
                $this->_redirect('*/*/edit', array('id' => $_SESSION['ticket_id']));
            }catch(Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
}